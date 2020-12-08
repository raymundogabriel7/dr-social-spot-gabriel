<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    public function commented_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'commented_by');
    }

    public function replies()
    {
        return $this->hasMany('App\Models\Replies', 'comment_id')->with('replied_user');
    }

    public function post()
    {
        return $this->hasOne('App\Models\Posts', 'id', 'post_id');
    }
}
