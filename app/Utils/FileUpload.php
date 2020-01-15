<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Str;

class FileUpload {
    public static function uploadPhoto() {
		request()->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);
		$file = request()->file('photo');
	    $time = Carbon::now();
	    $extension = $file->getClientOriginalExtension();
	    $directory = date_format($time, 'Y') . '/' . date_format($time, 'm');
		$filename = Str::random(5).date_format($time,'d').rand(1,9).date_format($time,'h').".".$extension;
	    $uploaded_filename = $file->storeAs($directory, $filename, 'public');
	    if ($uploaded_filename) {
	        return response()->json([
                'url' => url('storage/' . $uploaded_filename)
            ], 200);
	    }
        return response()->json('upload error', 400);
    }
}
