<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:255'
            ],
        ];
        if ($this->method() === 'PUT') {
            $rules['email'] = [
                'required',
                'email',
                'max:255',
                "unique:users,email,{$this->id}"
            ];

            $rules['password'] = [
                'nullable',
                'min:6',
                'max:100',
            ];
        }
    }
}
