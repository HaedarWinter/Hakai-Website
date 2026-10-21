<?php

namespace App\Notifications;

use App\Models\Tugas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskSubmitted extends Notification implements ShouldQueue
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
        $employeeName = $this->tugas->user?->nama ?? 'Karyawan';

        return (new MailMessage)
            ->subject('📤 Tugas Disubmit: ' . $this->tugas->tugas)
            ->greeting('Halo ' . $notifiable->nama . '!')
            ->line($employeeName . ' telah mensubmit tugas yang perlu Anda review.')
            ->line('')
            ->line('**Detail Tugas:**')
            ->line('📌 ' . $this->tugas->tugas)
            ->line('👤 Dikerjakan oleh: ' . $employeeName)
            ->action('Review Tugas', url('/tugas'))
            ->line('')
            ->line('Silakan review dan berikan persetujuan atau catatan.')
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
            'type' => 'task_submitted',
            'message' => $this->tugas->user?->nama . ' telah mensubmit tugas: ' . $this->tugas->tugas,
            'submitted_by' => $this->tugas->user?->nama,
        ];
    }
}
