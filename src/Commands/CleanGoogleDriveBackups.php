<?php

namespace AhmedWeb\LaravelBackupService\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;

class CleanGoogleDriveBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:clean-google-drive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Keep the latest backup file on Google Drive and delete all others';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $disk = Storage::disk('google_drive');

        if (!method_exists($disk->getAdapter(), 'getLatestUploadedFile')) {
            $this->error('GoogleDriveAdapter is missing necessary methods.');
            return;
        }

        $latest = $disk->getAdapter()->getLatestUploadedFile();
        if (!$latest) {
            $this->warn('No files found on Google Drive.');
            return;
        }

        $this->info('Keeping latest file: ' . $latest['name']);
        $disk->getAdapter()->deleteAllExceptLatest();

        $this->info('Old backups deleted successfully.');
    }
}
