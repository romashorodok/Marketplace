<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Image;
use Illuminate\Http\UploadedFile;

class ImageService
{
    public function __construct(
        private readonly Image             $image,
        private readonly FileUploadService $uploader
    )
    {
    }

    public function create(UploadedFile $file): Image
    {
        return $this->image->newQuery()->create([
            'path' => $this->uploader->upload($file)
        ]);
    }

    public function update(int $id, UploadedFile $file): Image
    {
        $filePath = $this->uploader->upload($file);
        $image = $this->image->newQuery()->find($id);

        if ($filePath) {
            $this->uploader->delete($image->path);
            $image->update(['path' => $filePath]);
        }

        return $image->refresh();
    }
}
