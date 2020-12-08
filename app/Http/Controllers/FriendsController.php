<?php

namespace App\Http\Controllers;

use App\Models\Friends;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class FriendsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $users = User::all();
        
        foreach ($users as $user) {
            $post = new Friends();
            $post->user_id= $user->id;
            $post->friend_id = $request->friend_id;
            $post->save();
        }

        return response()->json(['status' => 'success'], 200);
    }
}
