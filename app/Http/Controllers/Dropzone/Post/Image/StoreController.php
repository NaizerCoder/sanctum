<?php

namespace App\Http\Controllers\Dropzone\Post\Image;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dropzone\Post\Image\ImageRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class StoreController extends Controller
{
    public function __invoke(ImageRequest $request)
    {
        $data = $request->validated();
        $image = $data['image'];

        $name = md5(Carbon::now() . '_'. $image->getClientOriginalName()). '.' .$image->getClientOriginalExtension();
        $name = 'content_'.$name;
        $filePath = Storage::disk('public')->putFileAs('/images',$image,$name);

        return response()->json(['url'=> url('/storage/'.$filePath)]);
    }
}
