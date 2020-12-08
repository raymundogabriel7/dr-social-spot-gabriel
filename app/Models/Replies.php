<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Replies extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'replies';

    public function replied_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'replied_by');
    }

    public function comment()
    {
        return $this->hasOne('App\Models\Comment', 'id', 'comment_id');
    }
}
