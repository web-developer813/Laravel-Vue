<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{

    protected $fillable = array('user_id', 'likable_id','likable_type');

    public function likable()
    {
        return $this->morphTo();
    }
}
