<?php

namespace App\Http\Controllers;

use App\Models\Likes;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class LikesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $post = new Likes();
        $post->post_id= $request->post_id;
        $post->liked_by = Auth::user()->id;

        if($post->save()) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 400);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $like = Likes::find($id);

        if($like->delete()) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 400);
    }
}
