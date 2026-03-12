<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message)
    {
    }

    /**
     * Channel: private-conversation.{conversation_id}
     * Flutter subscribes to this channel to receive new messages in real-time
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Payload sent to Flutter client
     */
    public function broadcastWith(): array
    {
        return [
            'id'              => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id'       => $this->message->sender_id,
            'sender_name'     => $this->message->sender->name,
            'sender_avatar'   => $this->message->sender->avatar ?? null,
            'message_text'    => $this->message->message_text,
            'file_url'        => $this->message->file_url,
            'file_type'       => $this->message->file_type,
            'is_read'         => $this->message->is_read,
            'created_at'      => $this->message->created_at->toISOString(),
        ];
    }
}