<?php

namespace App\Events;

use App\Models\Conversation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Conversation $conversation)
    {
    }

    /**
     * Channel: private-user.{user_id}
     * Each user listens on their own private channel for global notifications
     * (new conversation request, acceptance, rejection)
     */
    public function broadcastOn(): array
    {
        // Notify both participants
        return [
            new PrivateChannel('user.' . $this->conversation->user_one_id),
            new PrivateChannel('user.' . $this->conversation->user_two_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'conversation.status';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversation->id,
            'product_id'      => $this->conversation->product_id,
            'user_one_id'     => $this->conversation->user_one_id,
            'user_two_id'     => $this->conversation->user_two_id,
            'status'          => $this->conversation->status,
            'updated_at'      => $this->conversation->updated_at->toISOString(),
        ];
    }
}