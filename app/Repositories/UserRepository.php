<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByProvider(string $provider, string $providerId): ?User
    {
        return User::where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();
    }

    public function updateOrCreateByProvider(
        string $provider,
        string $providerId,
        array $data
    ): User {
        return User::updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $providerId,
            ],
            $data
        );
    }
}
