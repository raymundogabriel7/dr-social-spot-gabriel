<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comments', 'post_id')->with(['commented_user', 'replies'])->where('parent_id', '=', NULL);
    }

    public function like()
    {
        return $this->hasOne('App\Models\Likes', 'post_id')->with(['liked_by_user'])->where('liked_by', '=', Auth::user()->id);
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Likes', 'post_id');
    }
}
