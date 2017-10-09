<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\File;

use AWS;
use Storage;

class BackupDbToS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database to S3';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // mysql dump to server
        $file = storage_path() . 'backup-' . str_slug(Date('Y-m-d')) . '.sql';
        exec('mysqldump -f --user=' . getenv('DB_USERNAME') . ' --password=' . getenv('DB_PASSWORD') . ' --host=' . getenv('DB_HOST') . ' ' . getenv('DB_DATABASE') . ' > ' . $file);

        // move to s3
        $key = str_slug(Date('Y-m-d H:i:s')) . '_' . time() . '.sql';
        Storage::disk('backups')->putFileAs(str_slug(getenv('APP_ENV')), new File($file), $key, 'private');

        // delete file
        unlink($file);
    }
}
