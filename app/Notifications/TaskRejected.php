<?php

namespace App\Notifications;

use App\Models\Tugas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskRejected extends Notification implements ShouldQueue
{
    use Queueable;

    protected Tugas $tugas;

    /**
     * Create a new notification instance.
     */
    public function __construct(Tugas $tugas)
    {
        $this->tugas = $tugas;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $adminName = $this->tugas->creator?->nama ?? 'Admin';
        $catatan = $this->tugas->catatan ?? 'Tidak ada catatan';

        return (new MailMessage)
            ->subject('❌ Tugas Ditolak: ' . $this->tugas->tugas)
            ->greeting('Halo ' . $notifiable->nama . '!')
            ->line('Mohon maaf, tugas Anda ditolak oleh ' . $adminName . '.')
            ->line('')
            ->line('**Detail Tugas:**')
            ->line('📌 ' . $this->tugas->tugas)
            ->line('❌ Status: Ditolak')
            ->line('')
            ->line('**Catatan dari Admin:**')
            ->line('💬 ' . $catatan)
            ->action('Perbaiki & Submit Ulang', url('/tugas'))
            ->line('')
            ->line('Silakan perbaiki sesuai catatan dan submit ulang.')
            ->salutation('Salam, Sistem Manajemen Tugas');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tugas_id' => $this->tugas->id,
            'tugas' => $this->tugas->tugas,
            'type' => 'task_rejected',
            'message' => 'Tugas Anda ditolak: ' . $this->tugas->tugas,
            'catatan' => $this->tugas->catatan,
            'rejected_by' => $this->tugas->creator?->nama,
        ];
    }
}
