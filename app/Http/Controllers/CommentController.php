<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comments;
use Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = new Comments();
        $comment->comment= $request->comment;
        $comment->post_id= $request->post_id;
        $comment->commented_by= Auth::user()->id;

        if($request->has('parent_id')) {
            $comment->parent_id = $request->parent_id;
        }

        if($comment->save()) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 400);
    }
}
