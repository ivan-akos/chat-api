<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::select('*')->whereNotNull('email_verified_at');

        if(!empty($request->activeOnly) && $request->activeOnly)
        {
             $users->whereNotNull('last_active_at')
                ->where('last_active_at', '>=', now()->subMinutes(15)); //this should probably not be hard coded :(
        }

        if(!empty($request->search))
        {
            $users->where(function($users) use($request) {

                $searchTerm = '%' . $request->search . '%';

                $users->orWhereRaw('LOWER(name) LIKE ?', $searchTerm)
                    // ->orWhere('email', 'LIKE', $searchTerm)
                    ;
            });
        }

        if(!empty($request->per_page))
        {
            $per_page = $request->per_page;

            $users->paginate($per_page);
        }


        return response()->json($users->get());
    } 
}
