<?php

namespace App\Services\Contracts;

use App\DTOs\RegisterDTO;
use App\DTOs\LoginDTO;
use App\Models\User;

interface AuthServiceInterface
{
    public function register(RegisterDTO $dto): User;

    public function login(LoginDTO $dto): User;
}

