<?php

namespace AhmedWeb\LaravelBackupService;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

use Illuminate\Filesystem\FilesystemAdapter;
use AhmedWeb\LaravelBackupService\Services\GoogleDriveAdapter;
use AhmedWeb\LaravelBackupService\Commands\DeleteOldBackupFiles;
use AhmedWeb\LaravelBackupService\Commands\StoreLatestBackupLink;
use AhmedWeb\LaravelBackupService\Commands\CleanGoogleDriveBackups;

/**
 * This file is part of the Laravel Action Service Trait package.
 *
 * @author Prevail Ejimadu <prevailexcellent@gmail.com> (C)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class BackupServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Merge configs
        $this->mergeConfigFrom(__DIR__ . '/Config/BackupService.php', 'laravel-backup-service');
        $this->commands([
            CleanGoogleDriveBackups::class,
            StoreLatestBackupLink::class,
            DeleteOldBackupFiles::class,
        ]);
    }
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/Config/BackupService.php' => config_path('laravel-backup-service.php'),
            __DIR__ . '/Config/backup.php' => config_path('backup.php'),
            __DIR__ . '/Providers/GoogleDriveStorageProvider.php' => app_path('Providers/GoogleDriveStorageProvider.php'),
        ], 'laravel-backup-service');
    }
}
