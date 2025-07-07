<?php

namespace App\Http\Requests\Auth;

use App\DTOs\RegisterDTO;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    // Convert request data to a RegisterDTO instance
    public function toDTO(): RegisterDTO
    {
        return new RegisterDTO(
            name: $this->input('name'),
            email: $this->input('email'),
            password: $this->input('password')
        );
    }
}
