<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LikableTrait;
use App\Traits\SearchableTrait;
use GetStream\StreamLaravel\Enrich;
use GetStream\StreamLaravel\Eloquent\Utils;

class Post extends Model
{
    use \GetStream\StreamLaravel\Eloquent\ActivityTrait;
    use SoftDeletes,LikableTrait,SearchableTrait;

    protected $fillable = ['content'];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'liked_by' => 'array',
    ];

    public function media()
    {
        return $this->hasMany(App\PostMedia::class, 'post_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function volunteer()
    {
        return $this->belongsTo('App\Volunteer', 'user_id', 'user_id');
    }

    public function owner()
    {
        return $this->belongsTo('App\Volunteer', 'user_id', 'user_id');
    }

    public function scopeOrderedByCreationDate($query)
    {
        return $query
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
    }

    public function activityVerb()
    {
        return 'post';
    }

    public function activityLazyLoading()
    {
        return array('volunteer');
    }

    public function activityActorMethodName()
    {
        return 'volunteer';
    }
}
