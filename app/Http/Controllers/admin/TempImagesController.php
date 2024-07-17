<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        $image = $request->image;

        if(!empty($image)){
            $ext = $image->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            $tempimage = new TempImage();
            $tempimage->image = $filename;
            $tempimage->save();
            
            $image->move(public_path().'/temp', $filename);

            //Generate thumbnail
            $sourcePath = public_path().'/temp/'.$filename;
            $destPath = public_path().'/temp/thumb/'.$filename;
            $image = Image::read($sourcePath);
            $image->resize(300, 275);
            $image->save($destPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempimage->id,
                'ImagePath' => asset('/temp/thumb/'.$filename),
                'message' => 'Image uploaded successfully',
            ]);
        }
    }
}
