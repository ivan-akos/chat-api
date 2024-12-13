<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    //TODO: Proper validation, denyRequest, showPending


    public function sendRequest($contact_id)
    {
        $user = auth('sanctum')->user();

        $contact = Contact::firstOrCreate([
            'user_id' => $user->id, 
            'contact_id' => $contact_id,
        ]);

        return response()->json(['message' => 'Contact request sent!', 'contact' => $contact]);
    }

    public function acceptRequest($contact_id)
    {
        $user = auth('sanctum')->user();

        if(!User::findOrFail($contact_id)){
            return response()->json([
                'message' => 'User not found'
            ]);
        }

        //           user_id and contact_id are flipped \/ here
        $contactRequest = Contact::where('contact_id', $user->id)
                                ->where('user_id', $contact_id)
                                ->where('accepted', false)
                                ->firstOrFail();

        $contactRequest->update(['accepted' => true]);

        return response()->json([
            'message' => 'Contact request accepted!',
            'contact' => $contactRequest,
        ]);
    }
}
