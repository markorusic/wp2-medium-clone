<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Utils\FileUpload;

class FileController extends Controller
{
    public function uploadPhoto(Request $request) {
        return FileUpload::uploadPhoto();
    }
}
