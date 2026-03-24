<?php

namespace App\Events;

use App\Models\ContactShare;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

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
        return [
            new PrivateChannel('user.' . $this->share->requester_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'contact.share.status';
    }

    public function broadcastWith(): array
    {
        $payload = [
            'share_id'        => $this->share->id,
            'conversation_id' => $this->share->conversation_id,
            'status'          => $this->share->status,
        ];

        if ($this->share->status === 'accepted' && $this->contactData) {
            $payload['contact'] = $this->contactData;
        }

        return $payload;
    }
}