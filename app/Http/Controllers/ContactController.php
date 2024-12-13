<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;

class ContactController extends Controller
{

    public function sendRequest($contact_id)
    {
        $user = auth('sanctum')->user();

        User::findOrFail($contact_id);

        $contact = Contact::firstOrCreate([
            'user_id' => $user->id, 
            'contact_id' => $contact_id,
        ]);

        if(!$contact->accepted){
            return response()->json(['message' => 'Contact request already sent!', 'contact' => $contact], 409);
        }

        return response()->json(['message' => 'Contact request sent!', 'contact' => $contact]);
    }

    public function acceptRequest($contact_id)
    {
        $user = auth('sanctum')->user();

        User::findOrFail($contact_id);

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

    public function denyRequest($contact_id)
    {
        $user = auth('sanctum')->user();

        User::findOrFail($contact_id);

        //           user_id and contact_id are flipped \/ here
        $contactRequest = Contact::where('contact_id', $user->id)
                                ->where('user_id', $contact_id)
                                ->where('accepted', false)
                                ->firstOrFail();

        $contactRequest->delete();

        return response()->json([
            'message' => 'Contact request denied!',
            'contact' => $contactRequest,
        ]);
    }

    public function showContacts()
    {
        $contacts = auth('sanctum')->user()->contacts()->get();

        if ($contacts->isEmpty()) {
            return response()->json(['message' => 'No contacts found'], 404);
        }

        return response()->json(['message' => 'Contacts retrieved'], $contacts);
    }

    public function showPending()
    {
        $contacts = auth('sanctum')->user()->contacts()->where('accepted', false)->get();

        if ($contacts->isEmpty()) {
            return response()->json(['message' => 'No pending contacts found'], 404);
        }

        return response()->json(['message' => 'Pending contacts retrieved'], $contacts);
    }
}
