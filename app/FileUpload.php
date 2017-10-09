<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Cloudder;

class FileUpload extends Model
{
    protected $guarded = [];
    protected $fillable = ['key','cloudinary_key','uploaded'];

    # get url
    public function getUrlAttribute()
    {
        if (Request::server('HTTP_X_FORWARDED_PROTO') == 'https' && !is_null($this->attributes['cloudinary_key']) && !empty($this->attributes['cloudinary_key'])) {
            return Cloudder::showSecure($this->attributes['cloudinary_key']);
        } elseif (!is_null($this->attributes['cloudinary_key']) && !empty($this->attributes['cloudinary_key'])) {
            return Cloudder::show($this->attributes['cloudinary_key']);
        }
        return "https://" . getenv('AWS_BUCKET') . ".s3.amazonaws.com/" . $this->attributes['key'];
    }
}
