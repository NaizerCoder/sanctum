<?php

namespace App\Http\Controllers\Dropzone\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dropzone\Post\StoreRequest;
use App\Models\Image;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

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
            $name_prev = 'prev_'.$name;
            $filePath = Storage::disk('public')->putFileAs('/images',$image,$name);

            Image::create( [
                'path' => $filePath,
                'url' => url('/storage/'.$filePath),
                'prev_url' => url('/storage/images/'.$name_prev),
                'post_id' => $post->id
            ]);

            $image_prev = ImageManager::imagick()->read($image);

            $image_prev->resize(100, 100)->save(storage_path('app/public/images/'.$name_prev));


        }

        return response()->json('success','200');
    }
}
