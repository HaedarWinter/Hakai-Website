<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskComment;
use App\Models\Tugas;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    /**
     * List all comments for a task (with nested replies).
     */
    public function index(Tugas $tugas)
    {
        // Get only top-level comments (no parent)
        $comments = TaskComment::with(['user:id,nama,profile_photo_path', 'replies.user:id,nama,profile_photo_path'])
            ->where('tugas_id', $tugas->id)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'asc')
            ->get();

        $formattedComments = $this->formatComments($comments);

        return response()->json([
            'success' => true,
            'data' => $formattedComments,
        ]);
    }

    /**
     * Format comments recursively (for nested replies).
     */
    private function formatComments($comments, $depth = 0)
    {
        return $comments->map(function ($comment) use ($depth) {
            $formatted = [
                'id' => $comment->id,
                'content' => $comment->content,
                'parent_id' => $comment->parent_id,
                'depth' => $depth,
                'user' => [
                    'id' => $comment->user->id,
                    'nama' => $comment->user->nama,
                    'photo' => $comment->user->profile_photo_url,
                ],
                'is_own' => $comment->user_id === auth()->id(),
                'created_at' => $comment->created_at->format('d M Y, H:i'),
                'created_at_human' => $comment->created_at->diffForHumans(),
                'replies' => [],
            ];

            // Recursively format replies (limit depth to 2 levels)
            if ($comment->replies && $comment->replies->count() > 0 && $depth < 2) {
                $formatted['replies'] = $this->formatComments($comment->replies, $depth + 1);
            }

            return $formatted;
        });
    }

    /**
     * Store a new comment (or reply).
     */
    public function store(Request $request, Tugas $tugas)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:task_comments,id',
        ], [
            'content.required' => 'Komentar tidak boleh kosong',
            'content.max' => 'Komentar maksimal 1000 karakter',
        ]);

        // Check if user has access to this task
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Admin can comment on tasks they created, employees can comment on their assigned tasks
        if ($user->isAdmin()) {
            if ($tugas->created_by !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke tugas ini',
                ], 403);
            }
        } else {
            if ($tugas->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke tugas ini',
                ], 403);
            }
        }

        // If replying, verify parent comment belongs to same task
        if (!empty($validated['parent_id'])) {
            $parentComment = TaskComment::find($validated['parent_id']);
            if (!$parentComment || $parentComment->tugas_id !== $tugas->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Komentar induk tidak valid',
                ], 400);
            }
        }

        $comment = TaskComment::create([
            'tugas_id' => $tugas->id,
            'user_id' => $user->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
        ]);

        $comment->load('user:id,nama,profile_photo_path');

        $parentId = $validated['parent_id'] ?? null;

        return response()->json([
            'success' => true,
            'message' => $parentId ? 'Balasan berhasil ditambahkan' : 'Komentar berhasil ditambahkan',
            'data' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'parent_id' => $comment->parent_id,
                'depth' => $comment->parent_id ? 1 : 0,
                'user' => [
                    'id' => $comment->user->id,
                    'nama' => $comment->user->nama,
                    'photo' => $comment->user->profile_photo_url,
                ],
                'is_own' => true,
                'created_at' => $comment->created_at->format('d M Y, H:i'),
                'created_at_human' => $comment->created_at->diffForHumans(),
                'replies' => [],
            ],
        ], 201);
    }

    /**
     * Delete a comment (only own comments or admin for their tasks).
     */
    public function destroy(TaskComment $comment)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Check permission
        if ($user->isAdmin()) {
            // Admin can delete any comment on tasks they created
            $tugas = $comment->tugas;
            if ($tugas->created_by !== $user->id && $comment->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus komentar ini',
                ], 403);
            }
        } else {
            // Employees can only delete their own comments
            if ($comment->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda hanya dapat menghapus komentar sendiri',
                ], 403);
            }
        }

        // Delete comment (cascade will delete replies)
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus',
        ]);
    }
}
