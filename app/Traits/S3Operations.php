<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait S3Operations {
    public function storeFile($file, $directory = 'uploads'){
        $path = $file->store($directory, 's3');
        return Storage::disk('s3')->url($path);
    }

    public function updateFile($newFile, $oldPath, $directory = 'uploads'){
        // Remove o arquivo antigo
        $this->deleteFile($oldPath);

        // Salva o novo arquivo
        return $this->storeFile($newFile, $directory);
    }

    public function deleteFile($filePath){
        return Storage::disk('s3')->delete($filePath);
    }
}
