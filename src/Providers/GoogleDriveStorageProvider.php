<?php

namespace AhmedWeb\LaravelBackupService\Providers;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\ServiceProvider;
use AhmedWeb\LaravelBackupService\Services\GoogleDriveAdapter;

class GoogleDriveStorageProvider extends ServiceProvider
{
    public function boot(): void
    {
        Log::info('🔌 GoogleDriveStorageProvider booted');

        Storage::extend('google', function ($app, $config) {
            // Prefer values from 'config/filesystems.php', fallback to package config
            $config = array_merge(
                config('laravel-backup-service.google_drive', []),
                $config
            );

            $client = new Client();
            $client->setClientId($config['clientId']);

            $client->setClientSecret($config['clientSecret']);

            $client->refreshToken($config['refreshToken']);

            $service = new Drive($client);
            $folderId = $config['folderId'] ?? 'root';

            $adapter = new GoogleDriveAdapter($service, $folderId);

            $flysystem = new \League\Flysystem\Filesystem($adapter);

            return new FilesystemAdapter($flysystem, $adapter, $config);
        });
    }
}
