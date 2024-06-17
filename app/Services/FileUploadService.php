<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public function upload($file, $path) :string
    {
        return Storage::disk()->put($path, $file);
    }
}
