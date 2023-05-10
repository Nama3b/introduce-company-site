<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class EditUserRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    #[ArrayShape([])] public function rules(): array
    {
        return [
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique',
            'password' => 'required|string|max:255',
            'full_name' => 'string|max:255',
            'birthday' => 'date',
            'phone_number' => 'string|max:255|unique',
            'address' => 'string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192',
        ];
    }
}
