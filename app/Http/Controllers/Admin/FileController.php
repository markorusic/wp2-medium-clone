<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\FileUpload;

class FileController extends Controller
{
    public function uploadPhoto(Request $request) {
        return FileUpload::uploadPhoto();
    }
}
