<?php

namespace App\Http\Requests\Dropzone\Post;

use Illuminate\Foundation\Http\FormRequest;

class UodateRequest extends FormRequest
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
            'images' => 'array',
            'content' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'обязательно для заполнения',
        ];
    }
}
