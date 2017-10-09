<?php

namespace App\Friendships\Models;

use App\Friendships\Models\Friendship;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FriendFriendshipGroups
 * @package Hootlex\Friendships\Models
 */
class FriendshipMessage extends Model
{
    protected $table = 'friendship_messages';
    /**
     * @var array
     */
    protected $fillable = ['friendship_id', 'message'];

    /**
     * @var bool
     */
    public $timestamps = false;

    
	public function friendship() {
		return $this->belongsTo(Friendship::class);
	}

}
