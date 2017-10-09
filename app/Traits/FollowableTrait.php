<?php

namespace App\Traits;
use Auth;
use App\Follow;

trait FollowableTrait {
	
    public function follows() {
        return $this->morphMany('App\Follow','followable');
    }

    public function following() {
        return $this->hasMany('App\Follow','user_id','id');
    }

}