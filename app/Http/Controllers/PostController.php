<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Friends;
use App\Models\Posts;
use Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $friend_ids = Friends::where('user_id', Auth::user()->id)->pluck('friend_id')->toArray();

        $posts = Posts::with(['user', 'comments', 'like', 'likes'])->where('user_id', Auth::user()->id)->orWhereIn('user_id', $friend_ids)->orderBy('created_at', 'DESC')->get();

        return response()->json(['status' => 'success', 'data' => $posts->toArray()], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePostRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePostRequest $request)
    {
        $post = new Posts();
        $post->description= $request->description;
        $post->user_id = Auth::user()->id;

        if($post->save()) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 400);
    }
}
