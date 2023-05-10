<?php

namespace App\Http\Requests\Post;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class StorePostRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    #[ArrayShape([])] public function rules(): array
    {
        return [
            'post_type' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192',
            'url' => 'nullable|string|max:255',
            'status' => 'required'
        ];
    }
}
