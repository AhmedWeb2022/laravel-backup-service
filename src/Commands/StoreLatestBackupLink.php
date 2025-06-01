<?php

namespace AhmedWeb\LaravelBackupService\Commands;

use App\Models\Backup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\Laravel\Facades\Telegram;

class StoreLatestBackupLink extends Command
{
    protected $signature = 'backup:store-link {project=default}';
    protected $description = 'Store or update the latest backup file link in the database';

    public function handle(): void
    {
        $project = $this->argument('project');
        $disk = Storage::disk('google_drive');
        $adapter = $disk->getAdapter();
        if (!method_exists($adapter, 'getLatestUploadedFile')) {
            $this->error('GoogleDriveAdapter does not have getLatestUploadedFile method.');
            return;
        }

        $latestFile = $adapter->getLatestUploadedFile();
        Log::info($latestFile);
        if (!$latestFile) {
            $this->warn("No backup file found for project: $project");
            return;
        }

        // Backup::updateOrCreate(
        //     ['project_slug' => $project],
        //     [
        //         'project_name' => $project,
        //         'file_link' => $latestFile['url']
        //     ],
        // );
        $updates = Telegram::getUpdates();
        $chat_id = $updates[0]['message']['chat']['id'];
        Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => "Backup link stored for project '{$project}': " . $latestFile['url'],
        ]);
        $this->info("Backup link stored for project '{$project}': " . $latestFile['url']);
    }
}
