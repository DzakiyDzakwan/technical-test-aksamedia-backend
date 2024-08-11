<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function avatar($employee_id, $file_name)
    {
        $path = storage_path("/public/employee_{$employee_id}/{$file_name}");
        return response()->file($path);
    }
}
