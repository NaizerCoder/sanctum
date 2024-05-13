<?php

namespace App\Http\Controllers\Dropzone\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dropzone\Post\PostResource;
use App\Models\Post;

class IndexController extends Controller
{
    public function __invoke()
    {
        $post = POST::latest()->first();
        return new PostResource($post);
    }
}
