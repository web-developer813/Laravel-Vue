<?php
namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['follow_id'];

	/**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'int',
    ];
		
    /**
     * Get the user that has the follow.
     */
    public function followable()
    {
        return $this->morphTo();
    }
	
}