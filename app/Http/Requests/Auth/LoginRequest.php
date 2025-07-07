<?php

namespace App\Http\Requests\Auth;

use App\DTOs\LoginDTO;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    // Convert request data to a LoginDTO
    public function toDTO(): LoginDTO
    {
        return new LoginDTO(
            email: $this->input('email'),
            password: $this->input('password')
        );
    }
}
