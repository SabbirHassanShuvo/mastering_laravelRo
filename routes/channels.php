<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

// Receives: conversation requests, contact share responses, global alerts
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// ── Private Conversation Channel ─────────────────────────────────────────
// Flutter subscribes: Echo.private('conversation.{conversationId}')
// Receives: new messages, read receipts, pickup updates
// Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
//     // For testing/production diagnostics, allow ADMIN to join any channel
//     // if ($user->role === 'admin' || $user->role === 'ADMIN') {
//     //     return true;
//     // }

//     $conversation = Conversation::find($conversationId);

//     if (!$conversation) {
//         return false;
//     }

//     return $conversation->user_one_id === $user->id
//         || $conversation->user_two_id === $user->id;
// });



Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    Log::info('hit channel', ['user' => $user ? $user->id : null]);
    return $user ? Conversation::where('id', $conversationId)
                         ->where(function ($query) use ($user) {
                             $query->where('user_one_id', $user->id)
                                   ->orWhere('user_two_id', $user->id);
                         })->exists() : false;
});