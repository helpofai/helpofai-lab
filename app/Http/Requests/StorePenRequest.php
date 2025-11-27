<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pens,slug'],
            'description' => ['nullable', 'string', 'max:500'],
            'html' => ['nullable', 'string'],
            'css' => ['nullable', 'string'],
            'js' => ['nullable', 'string'],
            'settings' => ['nullable', 'array'],
            'visibility' => ['required', 'in:public,unlisted,private'],
            'status' => ['required', 'in:draft,published'],
        ];
    }
}
