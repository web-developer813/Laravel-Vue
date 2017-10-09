<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cloudder;

class Photo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'key','cloudinary_key', 'type', 'photoable_type', 'photoable_id', 'uploaded'
    ];

    # get url
    public function getUrlAttribute()
    {
        if (!is_null($this->attributes['cloudinary_key']) && !empty($this->attributes['cloudinary_key'])) {
            return Cloudder::secureShow($this->attributes['cloudinary_key']);
        }
        return "https://" . getenv('AWS_BUCKET') . ".s3.amazonaws.com/" . $this->attributes['key'];
    }
}
