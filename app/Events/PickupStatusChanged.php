<?php

namespace App\Events;

use App\Models\Pickup;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PickupStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Pickup $pickup)
    {
    }

    /**
     * Channel: private-conversation.{conversation_id}
     * Both users in the conversation get pickup status updates
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->pickup->conversation_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'pickup.status';
    }

    public function broadcastWith(): array
    {
        return [
            'pickup_id'       => $this->pickup->id,
            'conversation_id' => $this->pickup->conversation_id,
            'product_id'      => $this->pickup->product_id,
            'pickup_date'     => $this->pickup->pickup_date,
            'pickup_time'     => $this->pickup->pickup_time,
            'location'        => $this->pickup->location,
            'status'          => $this->pickup->status,
            'updated_at'      => $this->pickup->updated_at->toISOString(),
        ];
    }
}