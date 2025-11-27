<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $pen = $this->route('pen');

        return $pen !== null && $this->user()?->can('update', $pen);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $pen = $this->route('pen');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('pens', 'slug')->ignore($pen?->id),
            ],
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
