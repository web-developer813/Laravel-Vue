<?php

namespace App\Traits;

use App\Photo;
use App\Jobs\UploadPhotoToS3;
use Cloudder;
use Laravolt\Avatar\Facade as Avatar;

trait ProfilePhotoTrait
{
    # has photo
    public function hasProfilePhoto()
    {
        return $this->profile_photo_id ? true : false;
    }

    # get photo url
    public function getProfilePhotoAttribute()
    {
        if ($this->hasProfilePhoto()) {
            return $this->profilePhoto()->url;
        } else {
            $avatar = Avatar::create($this->name)->toBase64();
            $this->updateProfilePhoto($avatar->encoded);
            return $this->profilePhoto()->url;
        }
    }

    # name
    protected function name()
    {
        return $this->name;
    }

    # profile photo
    protected function profilePhoto()
    {
        return Photo::find($this->profile_photo_id);
    }

    # profile photo
    protected function profilePhotoUrl()
    {
        return Photo::find($this->profile_photo_id);
    }

    # update profile photo
    public function updateProfilePhoto($file)
    {
        //	 no photo
        if (!$file) {
            return true;
        }
        // upload to s3
        //dispatch(new UploadPhotoToS3($photo, $file, 200, 200, 'png'));

        $cloudinary = Cloudder::upload($file);
        // create new upload
        $photo = Photo::create([
            'cloudinary_key' => $cloudinary->getResult()['public_id'],
            'photoable_type' => get_class($this),
            'photoable_id' => $this->id
        ]);

        //  update entity
        $this->update(['profile_photo_id' => $photo->id]);

    }
}
