<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskComment;
use App\Models\Tugas;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $notifications = [];
        $unreadCount = 0;

        // Get user's last read timestamp
        $lastReadAt = $user->notifications_read_at;

        if ($user->isAdmin()) {
            // Admin: Notify about tasks waiting for approval (submitted)
            // Filter by tasks created by this admin
            $pendingApprovals = Tugas::with('user')
                ->where('created_by', $user->id)
                ->where('status', 'submitted')
                ->latest('updated_at')
                ->take(5)
                ->get();

            // Count unread (notifications newer than last read)
            $countQuery = Tugas::where('created_by', $user->id)
                ->where('status', 'submitted');

            if ($lastReadAt) {
                $unreadCount = (clone $countQuery)->where('updated_at', '>', $lastReadAt)->count();
            } else {
                $unreadCount = $countQuery->count();
            }

            foreach ($pendingApprovals as $task) {
                $isRead = $lastReadAt && $task->updated_at <= $lastReadAt;

                $notifications[] = [
                    'id' => $task->id,
                    'type' => 'approval',
                    'message' => 'Tugas dari '.($task->user?->nama ?? 'Pengguna dihapus').' menunggu persetujuan',
                    'time' => $task->updated_at->diffForHumans(),
                    'icon' => 'fas fa-file-alt',
                    'color' => 'primary',
                    'url' => route('tugas.index'),
                    'is_read' => $isRead,
                ];
            }
        } else {
            // Employee: Notify about new tasks (pending) or rejected tasks
            // REMOVED: approved status - completed tasks don't need notifications
            $recentTasks = Tugas::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'rejected']) // Only actionable statuses
                ->where('updated_at', '>=', now()->subDays(3)) // Last 3 days
                ->latest('updated_at')
                ->take(5)
                ->get();

            // Count unread (notifications newer than last read)
            $countQuery = Tugas::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'rejected']);

            if ($lastReadAt) {
                $unreadCount = (clone $countQuery)->where('updated_at', '>', $lastReadAt)->count();
            } else {
                $unreadCount = $countQuery->count();
            }

            foreach ($recentTasks as $task) {
                $isRead = $lastReadAt && $task->updated_at <= $lastReadAt;
                $msg = '';
                $icon = '';
                $color = '';

                if ($task->status == 'pending') {
                    $msg = "Tugas baru: {$task->tugas}";
                    $icon = 'fas fa-tasks';
                    $color = 'warning';
                } elseif ($task->status == 'rejected') {
                    $msg = "Tugas ditolak: {$task->tugas}";
                    $icon = 'fas fa-exclamation-circle';
                    $color = 'danger';
                }

                $notifications[] = [
                    'id' => $task->id,
                    'type' => 'status',
                    'message' => $msg,
                    'time' => $task->updated_at->diffForHumans(),
                    'icon' => $icon,
                    'color' => $color,
                    'url' => route('tugas.index'),
                    'is_read' => $isRead,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'count' => $unreadCount,
            'notifications' => $notifications,
            'last_read_at' => $lastReadAt?->toIso8601String(),
        ]);
    }

    /**
     * Get comment/message notifications for the authenticated user.
     *
     * Reply notifications are now properly targeted:
     * - If it's a reply, only notify the parent comment author
     * - If it's a top-level comment, notify task owner/creator
     */
    public function messages(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $messages = [];
        $unreadCount = 0;

        // Get user's last read timestamp for messages
        $lastReadAt = $user->messages_read_at;

        // Base query: Get recent comments (not by this user)
        $query = TaskComment::with([
            'user:id,nama,profile_photo_path',
            'tugas:id,tugas,user_id,created_by',
            'parent:id,user_id', // Load parent to check reply targeting
        ])
            ->where('user_id', '!=', $user->id) // Exclude own comments
            ->where('created_at', '>=', now()->subDays(7)) // Last 7 days
            ->whereHas('user') // Skip comments whose author was deleted
            ->whereHas('tugas') // Ensure task exists (not deleted)
            ->latest('created_at');

        if ($user->isAdmin()) {
            // Admin sees:
            // 1. Top-level comments on tasks they created
            // 2. Replies TO their own comments (regardless of task ownership)
            $query->where(function ($q) use ($user) {
                // Top-level comments on their tasks
                $q->where(function ($subQ) use ($user) {
                    $subQ->whereNull('parent_id')
                        ->whereHas('tugas', function ($taskQ) use ($user) {
                            $taskQ->where('created_by', $user->id);
                        });
                })
                    // OR replies to their comments
                    ->orWhere(function ($subQ) use ($user) {
                        $subQ->whereNotNull('parent_id')
                            ->whereHas('parent', function ($parentQ) use ($user) {
                                $parentQ->where('user_id', $user->id);
                            });
                    });
            });
        } else {
            // Employee sees:
            // 1. Top-level comments on tasks assigned to them
            // 2. Replies TO their own comments
            $query->where(function ($q) use ($user) {
                // Top-level comments on their tasks
                $q->where(function ($subQ) use ($user) {
                    $subQ->whereNull('parent_id')
                        ->whereHas('tugas', function ($taskQ) use ($user) {
                            $taskQ->where('user_id', $user->id);
                        });
                })
                    // OR replies to their comments
                    ->orWhere(function ($subQ) use ($user) {
                        $subQ->whereNotNull('parent_id')
                            ->whereHas('parent', function ($parentQ) use ($user) {
                                $parentQ->where('user_id', $user->id);
                            });
                    });
            });
        }

        $recentComments = $query->take(10)->get();

        // Count unread messages
        if ($lastReadAt) {
            $unreadCount = $recentComments->filter(function ($comment) use ($lastReadAt) {
                return $comment->created_at > $lastReadAt;
            })->count();
        } else {
            $unreadCount = $recentComments->count();
        }

        foreach ($recentComments as $comment) {
            // Skip if task was deleted (extra safety check)
            if (! $comment->tugas) {
                continue;
            }

            // Skip if the author was deleted between query and render.
            if (! $comment->user) {
                continue;
            }

            $isRead = $lastReadAt && $comment->created_at <= $lastReadAt;

            $taskTitle = strlen($comment->tugas->tugas) > 30
                ? substr($comment->tugas->tugas, 0, 30).'...'
                : $comment->tugas->tugas;

            $contentPreview = strlen($comment->content) > 50
                ? substr($comment->content, 0, 50).'...'
                : $comment->content;

            $messages[] = [
                'id' => $comment->id,
                'task_id' => $comment->tugas_id,
                'type' => $comment->parent_id ? 'reply' : 'comment',
                'user' => [
                    'id' => $comment->user->id,
                    'nama' => $comment->user->nama,
                    'photo' => $comment->user->profile_photo_url,
                ],
                'message' => $contentPreview,
                'task_title' => $taskTitle,
                'time' => $comment->created_at->diffForHumans(),
                'url' => route('tugas.index'),
                'is_read' => $isRead,
            ];
        }

        return response()->json([
            'success' => true,
            'count' => $unreadCount,
            'messages' => $messages,
            'last_read_at' => $lastReadAt?->toIso8601String(),
        ]);
    }

    /**
     * Mark task notifications as read.
     */
    public function markNotificationsRead(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->notifications_read_at = now();
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai dibaca',
            'read_at' => $user->notifications_read_at->toIso8601String(),
        ]);
    }

    /**
     * Mark message notifications as read.
     */
    public function markMessagesRead(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->messages_read_at = now();
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Pesan ditandai sebagai dibaca',
            'read_at' => $user->messages_read_at->toIso8601String(),
        ]);
    }
}
