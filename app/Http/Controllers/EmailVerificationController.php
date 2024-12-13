<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return response([
            'message' => 'Email verified successfully'
        ]);
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        
        return response()->json(['message' => 'Verification link sent!']);
    }
}
