<?php

namespace App\Events;

use App\Models\ContactShare;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ContactShareStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ContactShare $share,
        public ?array $contactData = null  // passed only when accepted
    ) {
    }

    /**
     * Channel: private-user.{requester_id}
     * Notify the requester that their contact request was accepted/rejected
     */
    public function broadcastOn(): array
    {
        Log::info('enter contact broadcast: ' . $this->share->conversation_id);
        return [
            new PrivateChannel('conversation.' . $this->share->conversation_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'contact';
    }

    public function broadcastWith(): array
    {
        // Load relations if not already loaded to ensure broadcast contains name data
        if (!$this->share->relationLoaded('requester') || !$this->share->relationLoaded('receiver')) {
            $this->share->load(['requester:id,name', 'receiver:id,name']);
        }

        $payload = [
            'id'              => $this->share->id,
            'type'            => 'contact_share',
            'conversation_id' => $this->share->conversation_id,
            'requester_id'    => $this->share->requester_id,
            'requester_name'  => $this->share->requester->name ?? 'User',
            'receiver_id'     => $this->share->receiver_id,
            'receiver_name'   => $this->share->receiver->name ?? 'User',
            'status'          => $this->share->status,
            'created_at'      => $this->share->created_at->toIso8601String(),
        ];

        if ($this->share->status === 'accepted' && $this->contactData) {
            $payload['contact'] = $this->contactData;
        }

        return $payload;
    }
}