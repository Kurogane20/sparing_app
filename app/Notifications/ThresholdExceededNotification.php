<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ThresholdExceededNotification extends Notification
{
    use Queueable;

    private $exceededData;

    public function __construct($exceededData)
    {
        $this->exceededData = $exceededData;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Kirim via database dan email
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Some parameters exceeded their thresholds.',
            'details' => $this->exceededData
        ];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Threshold Exceeded Alert')
            ->line('Some parameters exceeded their thresholds.')
            ->line('Details:')
            ->with('parameters', $this->exceededData);
    }
}
