<?php

namespace App\Http\Resources\Dropzone\Image;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'url'=> $this->url,
            'prev_url'=> $this->prev_url,
            'size' => Storage::disk('public')->size($this->path),
            'name' => str_replace('images/',' ',$this->path),
        ];
    }
}
