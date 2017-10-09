<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Image;
use AWS;
use Storage;

// class UploadPhotoToS3 extends Job implements ShouldQueue
class UploadPhotoToS3 extends Job
{
    use InteractsWithQueue, SerializesModels;

    protected $photo;
    protected $file;
    protected $width;
    protected $height;
    protected $filetype;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($photo, $file, $width=200, $height=200, $filetype = 'jpg')
    {
        $this->photo = $photo;
        $this->file = $file;
        $this->width = $width;
        $this->height = $height;
        $this->filetype = $filetype;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // initialize
        $image = Image::make($this->file);

        // resize image
        $image->fit($this->width, $this->height);
        // $image->resize($this->width, $this->height, function($constraint) {
        //     $constraint->aspectRatio();
        // });
        // $image->resizeCanvas($this->width, $this->height, null, false, '#ffffff');

        // encode
        if ($this->filetype == 'png')
            $image->encode('png')->save($this->file);
        else
            $image->encode('jpg', 90)->save($this->file);

        // upload to s3
        Storage::disk('s3')
            ->putFileAs(null, $this->file, $this->photo->key, 'public');

        // remove file and image
        unlink($this->file);
        $image->destroy();
        
        // update model to uploaded
         $this->photo->update(['uploaded' => 1]);
    }
}
