<?php

namespace App\Notifications;

use App\Models\Tugas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTaskAssigned extends Notification implements ShouldQueue
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
        $deadline = $this->tugas->tanggal_selesai?->format('d M Y, H:i') ?? '-';

        return (new MailMessage)
            ->subject('📋 Tugas Baru Diberikan: '.$this->tugas->tugas)
            ->greeting('Halo '.$notifiable->nama.'!')
            ->line('Anda mendapatkan tugas baru dari '.$adminName.'.')
            ->line('')
            ->line('**Detail Tugas:**')
            ->line('📌 '.$this->tugas->tugas)
            ->line('📅 Deadline: '.$deadline)
            ->line('⚡ Prioritas: '.ucfirst($this->tugas->priority ?? 'medium'))
            ->action('Lihat Tugas', url('/tugas'))
            ->line('')
            ->line('Segera kerjakan tugas ini sebelum deadline!')
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
            'type' => 'new_task',
            'message' => 'Anda mendapatkan tugas baru: '.$this->tugas->tugas,
            'created_by' => $this->tugas->creator?->nama,
        ];
    }
}
