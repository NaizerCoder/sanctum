<?php

namespace App\Http\Controllers\Dropzone\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dropzone\Post\StoreRequest;
use App\Models\Image;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $images = $data['images'];
        unset($data['images']);

        $post = POST::firstOrCreate($data);

        foreach($images as $image){
            $name = md5(Carbon::now() . '_'. $image->getClientOriginalName()). '.' .$image->getClientOriginalExtension();
            $filePath = Storage::disk('public')->putFileAs('/images',$image,$name);

            Image::create( [
                'path' => $filePath,
                'url' => url('/storage/'.$filePath),
                'post_id' => $post->id
            ]);
        }


        return response()->json('success','200');
    }
}