<?php

namespace AhmedWeb\LaravelBackupService\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteOldBackupFiles extends Command
{
    protected $signature = 'backup:delete-old-files';
    protected $description = 'Delete all old backup files from Google Drive, keeping only the latest one';

    public function handle(): void
    {
        $disk = Storage::disk('google_drive');
        $adapter = $disk->getAdapter();

        if (!method_exists($adapter, 'deleteAllExceptLatest')) {
            $this->error('GoogleDriveAdapter does not have deleteAllExceptLatest method.');
            return;
        }

        $adapter->deleteAllExceptLatest();

        $this->info("All old backup files deleted, only latest retained.");
    }
}
