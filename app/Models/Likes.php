<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'likes';

    public function liked_by_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'liked_by');
    }

    public function post()
    {
        return $this->hasMany('App\Models\Posts', 'id', 'post_id');
    }
}
