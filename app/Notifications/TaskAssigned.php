<?php

namespace App\Notifications;

use App\Models\Tugas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification implements ShouldQueue
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
        $priorityLabels = [
            'high' => '🔴 Tinggi',
            'medium' => '🟡 Sedang',
            'low' => '🟢 Rendah',
        ];

        $priority = $priorityLabels[$this->tugas->priority ?? 'medium'] ?? '🟡 Sedang';

        return (new MailMessage)
            ->subject('📋 Tugas Baru Ditugaskan: '.$this->tugas->tugas)
            ->greeting('Halo, '.$notifiable->nama.'!')
            ->line('Anda telah ditugaskan untuk mengerjakan tugas berikut:')
            ->line('')
            ->line('**📌 Tugas:** '.$this->tugas->tugas)
            ->line('**⏱️ Deadline:** '.$this->tugas->tanggal_selesai->format('d M Y, H:i'))
            ->line('**🎯 Prioritas:** '.$priority)
            ->line('')
            ->action('Lihat Detail Tugas', url('/tugas'))
            ->line('Harap selesaikan tugas sebelum deadline.')
            ->salutation('Salam, Tim Manajemen');
    }

    /**
     * Get the array representation of the notification for database.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tugas_id' => $this->tugas->id,
            'tugas' => $this->tugas->tugas,
            'type' => 'task_assigned',
            'message' => 'Anda mendapat tugas baru: '.$this->tugas->tugas,
            'deadline' => $this->tugas->tanggal_selesai->format('d M Y, H:i'),
        ];
    }
}
