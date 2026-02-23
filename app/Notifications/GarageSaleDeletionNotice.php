<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GarageSaleDeletionNotice extends Notification
{
    use Queueable;

    protected $sale;

    public function __construct($sale)
    {
        $this->sale = $sale;
    }

    // Only database notification
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'sale_id'     => $this->sale->id,
            'event_title' => $this->sale->event_title,
            'message'     => 'Your garage sale "' . $this->sale->event_title . '" will be deleted in 2 days.'
        ];
    }
}