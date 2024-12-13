<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function retrieveFrom($contact_id){
        $user = auth('sanctum')->user();

        User::findOrFail($contact_id);

        $messages = $user->receivedMessages()
            ->where('sender_id', $contact_id)
            ->get();

        if(empty($messages)) {
            return response()->json(['message' => 'No messages to retrieve.']);
        }

        $user->receivedMessages()
            ->where('sender_id', $contact_id)
            ->where('is_read', false)           // update unread messages
            ->update(['is_read' => true]);
    
        return response()->json([
            'message' => 'Messages retrieved', 
            'data' => $messages
        ]);
    }

    public function retrieveTo($contact_id)
    {    
        $user = auth('sanctum')->user();

        User::findOrFail($contact_id);

        $messages = $user->sentMessages()
            ->where('sender_id', $contact_id)
            ->get();

        if(empty($messages)) {
            return response()->json(['message' => 'No sent messages yet.']);
        }

        return response()->json([
            'message' => 'Messages retrieved',
            'data' => $messages
        ]);
    }

    public function sendMessage(Request $request, $contact_id)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $user = auth('sanctum')->user();

        User::findOrFail($contact_id);

        $message = Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $contact_id,
            'content' => $validated['content'],
        ]);

        return response()->json([
            'message' => 'Message sent successfully!',
            'data' => $message,
        ]);
    }
   
}
