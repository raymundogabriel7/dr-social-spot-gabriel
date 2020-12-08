<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRepliesRequest;
use App\Models\Replies;
use Auth;

class RepliesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRepliesRequest $request)
    {
        $reply = new Replies();
        $reply->reply= $request->reply;
        $reply->comment_id= $request->comment_id;
        $reply->replied_by= Auth::user()->id;


        if($reply->save()) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 400);
    }
}
