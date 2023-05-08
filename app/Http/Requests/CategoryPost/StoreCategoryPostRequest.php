<?php

namespace App\Http\Requests\CategoryPost;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class StoreCategoryPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    #[ArrayShape([])] public function rules(): array
    {
        return [
            'type' => 'required',
            'position' => 'required|integer',
            'status' => 'required'
        ];
    }
}
