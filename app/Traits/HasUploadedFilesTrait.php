<?php

namespace App\Traits;

use App\FileUpload;
use App\Jobs\UploadFileToS3;

trait HasUploadedFilesTrait {

	# update file
	public function updateFile($file, $name = 'file') {
		// no photo
		if (!$file) return true;
		
		// create new upload
		$upload = FileUpload::create([
			'key' => 'files/' . md5(get_class($this) . $this->id)
				. '/' . md5(str_random(10) . time())
				. '.' . $file->extension()
		]);

		// upload to s3
		dispatch(new UploadFileToS3($upload, $file));

		//  update entity
		$this->update([$name => $upload->id]);
	}

}