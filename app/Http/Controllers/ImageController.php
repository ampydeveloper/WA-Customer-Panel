<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
    {

        //set path blank initially
        $file = "";
        if ($request->uploadImage != null && $request->uploadImage != '') {

            //check if directory exist if not create one
            $path = public_path() . '/uploads';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $cover = $request->file('uploadImage');
            $extension = $cover->getClientOriginalExtension();
            $file = $cover->getFilename() . '.' . $extension;
            if (!Storage::disk('public')->put($cover->getFilename() . '.' . $extension,  File::get($cover))) {
                $file = "";
            } else {
                $file = 'uploads/' . $file;
            }
        }
        return $file;
    }
    
    public function deleteImage() {
      return true;
    }
}
