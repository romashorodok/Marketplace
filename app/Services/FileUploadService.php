<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;

class FileUploadService
{
    public function __construct(
        private FilesystemManager $fs
    )
    {
    }

    public function upload(UploadedFile $file): string
    {
        return $this->fs->disk('public')->putFile('images', $file);
    }

    public function delete(string $path): bool
    {
        return $this->fs->disk('public')->delete($path);
    }
}
