<?php

namespace App\Notifications;

use App\Models\Tugas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected Tugas $tugas;

    protected string $status;

    protected ?string $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Tugas $tugas, string $status, ?string $reason = null)
    {
        $this->tugas = $tugas;
        $this->status = $status;
        $this->reason = $reason;
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
        $isApproved = $this->status === 'approved';

        $subject = $isApproved
            ? '✅ Tugas Disetujui: '.$this->tugas->tugas
            : '❌ Tugas Ditolak: '.$this->tugas->tugas;

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Halo, '.$notifiable->nama.'!');

        if ($isApproved) {
            $mail->line('🎉 Selamat! Tugas Anda telah **disetujui**.')
                ->line('')
                ->line('**📌 Tugas:** '.$this->tugas->tugas)
                ->line('')
                ->line('Terima kasih atas kerja keras Anda!');
        } else {
            $mail->line('Maaf, tugas Anda telah **ditolak**.')
                ->line('')
                ->line('**📌 Tugas:** '.$this->tugas->tugas);

            if ($this->reason) {
                $mail->line('**📝 Alasan:** '.$this->reason);
            }

            $mail->line('')
                ->line('Silakan perbaiki dan upload ulang tugas Anda.');
        }

        return $mail
            ->action('Lihat Detail Tugas', url('/tugas'))
            ->salutation('Salam, Tim Manajemen');
    }

    /**
     * Get the array representation of the notification for database.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $isApproved = $this->status === 'approved';

        return [
            'tugas_id' => $this->tugas->id,
            'tugas' => $this->tugas->tugas,
            'status' => $this->status,
            'type' => $isApproved ? 'task_approved' : 'task_rejected',
            'message' => $isApproved
                ? 'Tugas Anda disetujui: '.$this->tugas->tugas
                : 'Tugas Anda ditolak: '.$this->tugas->tugas,
            'reason' => $this->reason,
        ];
    }
}
