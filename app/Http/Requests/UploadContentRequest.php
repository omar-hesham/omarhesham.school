<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadContentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $maxKb = config('platform.upload_max_mb', 20) * 1024;

        return [
            'type'      => ['required', 'in:pdf,image,audio'],
            'title'     => ['nullable', 'string', 'max:255'],
            'lesson_id' => ['nullable', 'exists:lessons,id'],
            'file'      => [
                'required', 'file',
                "max:{$maxKb}",
                'mimes:pdf,jpg,jpeg,png,webp,mp3,mp4,ogg,wav',
            ],
        ];
    }
}
