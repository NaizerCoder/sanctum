<?php

namespace App\Http\Controllers\Dropzone\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dropzone\Post\UpdateRequest;
use App\Models\Image;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Post $post)
    {

        $data = $request->validated();

        $imagesDel = $data['id_img_del'] ?? null; // $imagesDel = isset($data['id_img_del']) ? $data['id_img_del'] : null;
        $images = $data['images'] ?? null;

        unset($data['images'], $data['id_img_del'] );

        $curImages = $post->images;

        if($imagesDel){
            foreach ($curImages as $curImage){

                if(in_array($curImage->id,$imagesDel)){
                    Storage::disk('public')->delete($curImage->path);
                    Storage::disk('public')->delete(str_replace('images/','images/prev_', $curImage->path));
                    $curImage->delete();
                }
            }
        }

        //$post = POST::firstOrCreate($data);

        if($images) {

            foreach ($images as $image) {
                $name = md5(Carbon::now() . '_' . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
                $name_prev = 'prev_' . $name;
                $filePath = Storage::disk('public')->putFileAs('/images', $image, $name);

                Image::create([
                    'path' => $filePath,
                    'url' => url('/storage/' . $filePath),
                    'prev_url' => url('/storage/images/' . $name_prev),
                    'post_id' => $post->id
                ]);

                $image_prev = ImageManager::imagick()->read($image);

                $image_prev->resize(100, 100)->save(storage_path('app/public/images/' . $name_prev));
            }
        }

        return response()->json('success', '200');
    }
}
