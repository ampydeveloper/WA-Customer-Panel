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
        $imageName = "";
        if ($request->uploadImage != null && $request->uploadImage != '') {

            $cover = $request->file('uploadImage');
            $extension = $cover->getClientOriginalExtension();
            $file = $cover->getFilename() . '.' . $extension;

            if (Storage::disk('public')->put($request->user()->id . '/' . $file, file_get_contents($cover))) {
                $imageName = config('constant.base_url') . '/' . $request->user()->id . '/' . $file;
            }
        }
        return $imageName;
    }

    public function uploadImageFile(Request $request)
    {

        //set path blank initially
        $imageName = "";
        if ($request->uploadImage != null && $request->uploadImage != '') {

            $cover = $request->file('uploadImage');
            $extension = $cover->getClientOriginalExtension();
            $file = $cover->getFilename() . '.' . $extension;
            
            if(Storage::disk('public')->put($request->user()->id.'/'.$file, file_get_contents($cover))) {
                $imageName = config('constant.base_url').'/'.$request->user()->id.'/'.$file;
            }
        }
        return response()->json([
                    'status' => true,
                    'message' => 'Image Upload',
                    'data' => $imageName
                        ], 200);
    }
    
    public function deleteImage() {
      return true;
    }
}
