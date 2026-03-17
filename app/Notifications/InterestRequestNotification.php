<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class InterestRequestNotification extends Notification
{
    use Queueable;

    protected $product;
    protected $matcher;

    /**
     * Create a new notification instance.
     */
    public function __construct(Product $product, User $matcher)
    {
        $this->product = $product;
        $this->matcher = $matcher;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'type'       => 'interest_request',
            'title'      => 'New Interest!',
            'message'    => "{$this->matcher->name} is interested in your '{$this->product->title}'.",
            'product_id' => $this->product->id,
            'matcher_id' => $this->matcher->id,
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type'       => 'interest_request',
            'title'      => 'New Interest!',
            'message'    => "{$this->matcher->name} is interested in your '{$this->product->title}'.",
            'product_id' => $this->product->id,
            'matcher_id' => $this->matcher->id,
        ]);
    }
}
