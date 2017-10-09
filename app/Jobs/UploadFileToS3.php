<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Storage;

// class UploadPhotoToS3 extends Job implements ShouldQueue
class UploadFileToS3 extends Job
{
    use InteractsWithQueue, SerializesModels;

    protected $upload;
    protected $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($upload, $file)
    {
        $this->upload = $upload;
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Storage::disk('s3')
            ->putFileAs(null, $this->file, $this->upload->key, 'public');

        // remove file
        unlink($this->file);
        
        // update model to uploaded
         $this->upload->update(['uploaded' => 1]);
    }
}
