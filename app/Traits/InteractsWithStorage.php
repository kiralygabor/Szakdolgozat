<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait InteractsWithStorage
{
    /**
     * Store a file in the specified directory using the public disk.
     */
    protected function storeFile(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public');
    }

    /**
     * Store multiple files in the specified directory.
     */
    protected function storeFiles(array $files, string $directory): array
    {
        return array_map(
            fn($file) => $this->storeFile($file, $directory),
            $files
        );
    }
}
