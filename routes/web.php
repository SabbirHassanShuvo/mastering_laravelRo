<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\Web\Backend\Auth\AuthController;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\ProjectController;
use App\Http\Controllers\Web\Backend\SiteController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Artisan;


Route::get('/{page?}', function ($page = null) {
    return redirect()->route('backend.dashboard.index');
})->where('page', 'home|index');


Route::get('/run-migrate-fresh', function () {

    Artisan::call('migrate:fresh', [
        '--seed' => true
    ]);

    return "Database migrated fresh and seeded successfully!";
});

require_once __DIR__ .'/auth.php';
require_once __DIR__ .'/sabbir.php';

// use App\Events\TestRealtimeEvent;

// use App\Events\MessageSent;
// use App\Models\Conversation;
// use App\Models\Message;

Route::get('/backend/test-realtime', function () {
    $conversations = \App\Models\Conversation::with(['userOne', 'userTwo', 'product'])->latest()->take(10)->get();
    return view('backend.test-realtime', compact('conversations'));
})->name('backend.test_realtime');

Route::post('/backend/trigger-test-event', function () {
    $messageText = request('message', 'Hello from Reverb Check!');
    $userName = request('user_name', 'System Diagnostic');
    broadcast(new \App\Events\TestRealtimeEvent($messageText, $userName));
    return response()->json(['status' => 'Public Event Broadcasted!']);
})->name('backend.trigger_test_event');


// Route::post('/backend/trigger-private-event', function () {
//     $conversationId = request('conversation_id');
//     $messageText = request('message');
    
//     $conversation = Conversation::findOrFail($conversationId);
    
//     // Create a dummy message for broadcasting
//     $message = new Message([
//         'conversation_id' => $conversation->id,
//         'sender_id' => auth()->id() ?? $conversation->user_one_id,
//         'message_text' => $messageText,
//         'is_read' => false,
//     ]);
//     $message->id = rand(999, 9999); // Temporary ID for broadcast
//     $message->created_at = now();
    
//     // Ensure sender relationship exists for broadcastWith
//     $message->setRelation('sender', auth()->user() ?? $conversation->userOne);

//     broadcast(new MessageSent($message));
    
//     return response()->json(['status' => 'Private Event Broadcasted!', 'message' => $messageText]);
// })->name('backend.trigger_private_event');

// Route::get('/backend/conversation/{id}/messages', function ($id) {
//     return Message::where('conversation_id', $id)
//         ->with('sender')
//         ->oldest()
//         ->get();
// })->name('backend.get_messages');

