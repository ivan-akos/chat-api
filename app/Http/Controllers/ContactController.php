<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function sendRequest($contact_id)
    {
        $user = auth('sanctum')->user();

        $contact = Contact::firstOrCreate([
            'user_id' => $user->id, 
            'contact_id' => $contact_id,
        ]);

        return response()->json(['message' => 'Contact request sent!', 'contact' => $contact]);
    }
}
