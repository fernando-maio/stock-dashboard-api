<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\DTOs\RegisterDTO;
use App\DTOs\LoginDTO;
use App\Services\Contracts\AuthServiceInterface;
use App\Exceptions\UnauthorizedException;
use App\Repositories\Contracts\UserRepositoryInterface;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function register(RegisterDTO $dto): User
    {
        return $this->userRepository->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
        ]);
    }

    public function login(LoginDTO $dto): User
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw new UnauthorizedException();
        }

        return $user;
    }
}
