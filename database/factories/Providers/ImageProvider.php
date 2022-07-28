<?php

namespace Database\Factories\Providers;

class ImageProvider extends \Faker\Provider\Base
{
    public static function imagePath()
    {
        $path = storage_path('seed');

        $iterator = new \FilesystemIterator($path);

        $files = [];

        foreach ($iterator as $file) {
            $files[] = $file->getRealPath();
        }

        return self::randomElement($files);
    }
}
