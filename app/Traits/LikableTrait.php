<?php

namespace App\Traits;
use Auth;

trait LikableTrait {

    public function likes() {
        return $this->morphMany('App\Like','likable');
    }

    public function likedByMe()
    {
        // Returns like Object
        return $this->likes()->where('user_id', '=', Auth::id())->first();
    }

    public function likesTotal() {
        return $this->likes()->count();
    }

    public function likesByOthers() {
        // Returns Object with likes by other users
        return $this->likes()->where('user_id', '!=', Auth::id());
    }

}