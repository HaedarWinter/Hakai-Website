<?php

namespace App\Notifications;

use App\Models\Tugas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskApproved extends Notification implements ShouldQueue
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $adminName = $this->tugas->creator?->nama ?? 'Admin';

        return (new MailMessage)
            ->subject('✅ Tugas Disetujui: '.$this->tugas->tugas)
            ->greeting('Selamat '.$notifiable->nama.'! 🎉')
            ->line('Tugas Anda telah disetujui oleh '.$adminName.'.')
            ->line('')
            ->line('**Detail Tugas:**')
            ->line('📌 '.$this->tugas->tugas)
            ->line('✅ Status: Disetujui')
            ->action('Lihat Detail', url('/tugas'))
            ->line('')
            ->line('Terima kasih atas kerja keras Anda!')
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
            'type' => 'task_approved',
            'message' => 'Tugas Anda telah disetujui: '.$this->tugas->tugas,
            'approved_by' => $this->tugas->creator?->nama,
        ];
    }
}
