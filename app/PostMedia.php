<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    protected $fillable = ['post_id','cloudinary_key'];

    public function mediaType()
    {
        return $this->belongsTo(MediaType::class, 'media_type_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function fileUpload()
    {
        return $this->belongsTo(FileUpload::class, 'file_uploads_id');
    }
}
