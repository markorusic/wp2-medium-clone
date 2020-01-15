<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Str;

class FileUpload {
    public static function upload($file) {
        // Creating a new time instance, we'll use it to name our file and declare the path
	    $time = Carbon::now();
	    // Getting the extension of the file 
	    $extension = $file->getClientOriginalExtension();
	    // Creating the directory, for example, if the date = 18/10/2017, the directory will be 2017/10/
	    $directory = date_format($time, 'Y') . '/' . date_format($time, 'm');
	    // Creating the file name: random string followed by the day, random number and the hour
	    $filename = Str::random(5).date_format($time,'d').rand(1,9).date_format($time,'h').".".$extension;
	    // This is our upload main function, storing the image in the storage that named 'public'
	    $upload_success = $file->storeAs($directory, $filename, 'public');
	    // If the upload is successful, return the name of directory/filename of the upload.
	    if ($upload_success) {
	        return response()->json([
                'url' => url('storage/' . $upload_success)
            ], 200);
	    }
        return response()->json('upload error', 400);
    }
}
