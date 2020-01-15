<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Utils\FileUpload;

class FileController extends Controller
{
    public function upload(Request $request) {
        return FileUpload::upload($request->file('file'));
    }
}
