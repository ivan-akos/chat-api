<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function retrieveFrom($contact_id){
        $user = auth('sanctum')->user();

        $messages = $user->receivedMessages()
            ->where('sender_id', $contact_id)
            ->get();

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

        $messages = $user->sentMessages()
            ->where('sender_id', $contact_id)
            ->get();

        return response()->json([
            'message' => 'Messages retrieved',
            'data' => $messages
        ]);
    }
}
