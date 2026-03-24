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
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $message;
    public function __construct(Message $message)
    {
        $this->message = $message;
      
    }

    /**
     * Channel: private-conversation.{conversation_id}
     * Flutter subscribes to this channel to receive new messages in real-time
     */
   
  
  
  public function broadcastOn()
    {
        Log::info('enter broadcast: '.$this->message->conversation_id);

        return new PrivateChannel('conversation.'.$this->message->conversation_id);
    }
  
    public function broadcastAs(): string
    {
        return 'message';
    }

    /**
     * Payload sent to Flutter client
     */
    public function broadcastWith(): array
    {
        $this->message->loadMissing('sender.profile');

        return [
            'id'              => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id'       => $this->message->sender_id,
            'sender_name'     => $this->message->sender->name,
            'sender_avatar'   => optional($this->message->sender->profile)->avatar,
            'message_text'    => $this->message->message_text,
            'file_url'        => $this->message->file_url,
            'file_type'       => $this->message->file_type,
            'is_read'         => $this->message->is_read,
            'created_at'      => $this->message->created_at->toISOString(),
        ];
    }
}