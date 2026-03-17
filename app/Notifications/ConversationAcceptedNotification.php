<?php

namespace App\Notifications;

use App\Models\Conversation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ConversationAcceptedNotification extends Notification
{
    use Queueable;

    protected $conversation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
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
        $product = $this->conversation->product;
        $owner   = $product->user;

        return [
            'type'            => 'conversation_accepted',
            'title'           => 'Request Accepted!',
            'message'         => "{$owner->name} accepted your interest in '{$product->title}'. You can now chat.",
            'conversation_id' => $this->conversation->id,
            'product_id'      => $product->id,
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast($notifiable)
    {
        $product = $this->conversation->product;
        $owner   = $product->user;

        return new BroadcastMessage([
            'type'            => 'conversation_accepted',
            'title'           => 'Request Accepted!',
            'message'         => "{$owner->name} accepted your interest in '{$product->title}'. You can now chat.",
            'conversation_id' => $this->conversation->id,
            'product_id'      => $product->id,
        ]);
    }
}
