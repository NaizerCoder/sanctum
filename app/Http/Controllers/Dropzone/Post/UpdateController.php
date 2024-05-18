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

        $imagesIdDel = $data['id_img_del'] ?? null; // $imagesIdDel = isset($data['id_img_del']) ? $data['id_img_del'] : null; DROPZONE
        $imagesUrlDel = $data['url_img_del'] ?? null; // vueEditor

        $images = $data['images'] ?? null;

        unset($data['images'], $data['id_img_del'], $data['url_img_del']);

        $post->update($data); //update title + content

        /*УДАЛЕНИЕ ИЗОБРАЖЕНИЙ ИЗ DROPZONE*/
        $curImages = $post->images;
        if($imagesIdDel){
            foreach ($curImages as $curImage){

                if(in_array($curImage->id,$imagesIdDel)){
                    Storage::disk('public')->delete($curImage->path); //основное
                    Storage::disk('public')->delete(str_replace('images/','images/prev_', $curImage->path)); //превью
                    $curImage->delete();
                }
            }
        }

        /*УДАЛЕНИЕ ИЗОБРАЖЕНИЙ ИЗ VueEditor*/
        if($imagesUrlDel){
            foreach ($imagesUrlDel as $imageUrlDel){
                $pathRoot = $request->root().'/storage/'; //доменное имя + storage (получится images/name_file.ext)
                $nameImageUrlDel = str_replace($pathRoot,'',$imageUrlDel);
                Storage::disk('public')->delete($nameImageUrlDel);
            }
        }

        /*ДОБАВЛЕНИЕ ИЗОБРАЖЕНИЙ DROPBOX*/
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
