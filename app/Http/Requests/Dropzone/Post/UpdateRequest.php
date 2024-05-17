<?php

namespace App\Http\Requests\Dropzone\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'images' => 'array', //новые изображения
            'content' => 'nullable|string',
            'id_img_del' => 'nullable' //сюда попадет массив из ID изображений на удаление
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'обязательно для заполнения',
        ];
    }
}
