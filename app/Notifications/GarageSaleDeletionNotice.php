<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GarageSaleDeletionNotice extends Notification
{
    use Queueable;

    protected $sale;

    public function __construct($sale)
    {
        $this->sale = $sale;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // database + email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Garage Sale Deletion Warning')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your Garage Sale "' . $this->sale->event_title . '" will be deleted in 2 days.');
    }

    public function toArray($notifiable)
    {
        return [
            'sale_id' => $this->sale->id,
            'event_title' => $this->sale->event_title,
            'message' => 'Your garage sale will be deleted in 2 days.'
        ];
    }
}