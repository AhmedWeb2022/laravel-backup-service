<?php

namespace AhmedWeb\LaravelBackupService\Services;

use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use League\Flysystem\Config;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\FileAttributes;
use League\Flysystem\UnableToRetrieveMetadata;

class GoogleDriveAdapter implements FilesystemAdapter
{
    protected $service;
    protected $folderId;

    public function __construct(Drive $service, $folderId = null)
    {
        $this->service = $service;
        $this->folderId = $folderId;
    }

    public function getUrl($path): string
    {
        return 'https://drive.google.com/file/d/' . $this->getFileId($path) . '/view?usp=sharing';
    }
    public function write($path, $contents, Config $config): void
    {
        $fileMetadata = new DriveFile([
            'name' => basename($path),
            'parents' => $this->folderId ? [$this->folderId] : [],
        ]);

        try {
            $this->service->files->create($fileMetadata, [
                'data' => $contents,
                'mimeType' => 'application/zip',
                'uploadType' => 'multipart',
            ]);
        } catch (\Exception $e) {
            throw new UnableToWriteFile("Failed to write file at path: $path. Error: " . $e->getMessage());
        }
    }

    public function read($path): string
    {
        $fileId = $this->getFileId($path);
        if (!$fileId) {
            throw new UnableToReadFile("File not found at path: $path");
        }

        try {
            $response = $this->service->files->get($fileId, ['alt' => 'media']);
            return $response->getBody()->getContents();
        } catch (\Exception $e) {
            throw new UnableToReadFile("Failed to read file at path: $path. Error: " . $e->getMessage());
        }
    }

    public function delete($path): void
    {
        $fileId = $this->getFileId($path);
        if (!$fileId) {
            throw new UnableToDeleteFile("File not found at path: $path");
        }

        try {
            $this->service->files->delete($fileId);
        } catch (\Exception $e) {
            throw new UnableToDeleteFile("Failed to delete file at path: $path. Error: " . $e->getMessage());
        }
    }

    protected function getFileId($path): ?string
    {
        $files = $this->service->files->listFiles([
            'q' => "name = '" . basename($path) . "' and '{$this->folderId}' in parents",
            'fields' => 'files(id, name)',
        ]);

        foreach ($files->getFiles() as $file) {
            return $file->getId();
        }

        return null;
    }

    public function listFiles(): array
    {
        $files = $this->service->files->listFiles([
            'q' => "'{$this->folderId}' in parents and trashed = false",
            'fields' => 'files(id, name, webViewLink, createdTime)',
        ]);

        $result = [];

        foreach ($files->getFiles() as $file) {
            $result[] = [
                'id' => $file->getId(),
                'name' => $file->getName(),
                'url' => $file->getWebViewLink(),
                'created_at' => $file->getCreatedTime(),
            ];
        }

        return $result;
    }

    public function getLatestUploadedFile(): ?array
    {
        $files = $this->service->files->listFiles([
            'q' => "'{$this->folderId}' in parents and trashed = false",
            'orderBy' => 'createdTime desc',   // Sort by creation time, newest first
            'pageSize' => 1,                   // Only get the latest one
            'fields' => 'files(id, name, webViewLink, createdTime)',
        ]);

        $filesList = $files->getFiles();

        if (count($filesList) > 0) {
            $file = $filesList[0];

            return [
                'id' => $file->getId(),
                'name' => $file->getName(),
                'url' => $file->getWebViewLink(),
                'created_at' => $file->getCreatedTime(),
            ];
        }

        return null; // No files found
    }

    public function deleteAllExceptLatest(): void
    {
        $files = $this->service->files->listFiles([
            'q' => "'{$this->folderId}' in parents and trashed = false",
            'orderBy' => 'createdTime desc',
            'fields' => 'files(id, name, createdTime)',
        ]);

        $filesList = $files->getFiles();

        if (count($filesList) <= 1) {
            // Only one or no file — nothing to delete
            return;
        }

        // Keep the first one (most recent)
        $latestFile = array_shift($filesList);

        foreach ($filesList as $file) {
            $this->service->files->delete($file->getId());
        }
    }

    // Implement stubs for other required methods
    public function fileExists($path): bool
    {
        return $this->getFileId($path) !== null;
    }

    public function directoryExists($path): bool
    {
        return false;
    }

    public function writeStream($path, $resource, Config $config): void
    {
        $contents = stream_get_contents($resource);
        $this->write($path, $contents, $config);
    }

    public function readStream($path)
    {
        $contents = $this->read($path);
        $stream = fopen('php://temp', 'rb+');
        fwrite($stream, $contents);
        rewind($stream);

        return $stream;
    }

    public function deleteDirectory($path): void
    {
        throw new \Exception("Delete directory not supported for Google Drive.");
    }

    public function createDirectory($path, Config $config): void
    {
        throw new \Exception("Create directory not supported for Google Drive.");
    }

    public function setVisibility($path, $visibility): void
    {
        // Google Drive doesn't support direct visibility control.
    }

    public function visibility(string $path): FileAttributes
    {
        // Google Drive doesn't support direct visibility control, but we still need to return a FileAttributes object.
        return new FileAttributes($path, null, 'public');
    }

    public function mimeType(string $path): FileAttributes
    {
        // Normally, you should retrieve the MIME type from Google Drive.
        // If not supported, handle it accordingly.
        throw UnableToRetrieveMetadata::mimeType($path, 'MIME type retrieval not supported for Google Drive.');
    }

    public function lastModified(string $path): FileAttributes
    {
        // Normally, you should retrieve the last modified time from Google Drive.
        // If not supported, handle it accordingly.
        throw UnableToRetrieveMetadata::lastModified($path, 'Last modified time retrieval not supported for Google Drive.');
    }

    public function fileSize(string $path): FileAttributes
    {
        // Normally, you should retrieve the file size from Google Drive.
        // If not supported, handle it accordingly.
        throw UnableToRetrieveMetadata::fileSize($path, 'File size retrieval not supported for Google Drive.');
    }

    public function listContents($path = '', $deep = false): iterable
    {
        // Return an empty array to satisfy the contract.
        // Google Drive doesn't directly support listing contents as a typical filesystem does.
        return [];
    }

    public function move($source, $destination, Config $config): void
    {
        throw new \Exception("Move operation not supported for Google Drive.");
    }

    public function copy($source, $destination, Config $config): void
    {
        throw new \Exception("Copy operation not supported for Google Drive.");
    }
}
