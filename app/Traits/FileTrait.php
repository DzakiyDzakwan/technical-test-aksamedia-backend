<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait FileTrait
{
    private function saveImage($file, $employee_id)
    {
        $file_name = $file->getClientOriginalName();
        $storage_path = storage_path("/public/employee_{$employee_id}");

        if (!File::exists($storage_path)) {
            File::makeDirectory($storage_path, 0777, true, true);
        }

        copy($file->getPathname(), "{$storage_path}/{$file_name}");

        return $file_name;
    }

    private function saveDummyImage($file, $employee_id)
    {
        $file_source = public_path("/avatars/{$file}");

        if (!File::exists($file_source)) {
            return null;
        }

        $file_name = pathinfo($file_source, PATHINFO_FILENAME) . "." . pathinfo($file_source, PATHINFO_EXTENSION);
        $storage_path = storage_path("/public/employee_{$employee_id}");

        if (!File::exists($storage_path)) {
            File::makeDirectory($storage_path, 0777, true, true);
        }

        File::copy($file_source, "{$storage_path}/{$file_name}");

        return $file_name;
    }
}
