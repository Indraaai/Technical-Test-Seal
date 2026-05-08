<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    public function register(array $data): array
    {
        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'employee',
        ]);

        return [
            'user' => $user,
            'token' => $this->createToken($user),
        ];
    }

    public function login(array $data): array
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        return [
            'user' => $user,
            'token' => $this->createToken($user),
        ];
    }

    public function loginWithOAuth(string $provider, object $oauthUser): array
    {
        $user = $this->userRepository->updateOrCreateByProvider(
            provider: $provider,
            providerId: $oauthUser->getId(),
            data: [
                'name' => $oauthUser->getName()
                    ?? $oauthUser->getNickname()
                    ?? 'OAuth User',
                'email' => $oauthUser->getEmail(),
                'password' => Str::random(32),
                'role' => 'employee',
            ]
        );

        return [
            'user' => $user,
            'token' => $this->createToken($user),
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }

    private function createToken(User $user): string
    {
        $tokenName = $user->role === 'admin'
            ? 'admin-token'
            : 'employee-token';

        return $user->createToken($tokenName)->plainTextToken;
    }
}
