<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function redirect(string $provider): JsonResponse
    {
        $url = Socialite::driver($provider)
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return ApiResponse::success([
            'url' => $url,
        ], 'OAuth redirect URL berhasil dibuat.');
    }

    public function callback(string $provider): JsonResponse
    {
        $oauthUser = Socialite::driver($provider)
            ->stateless()
            ->user();

        $result = $this->authService->loginWithOAuth($provider, $oauthUser);

        return ApiResponse::success([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
            'token_type' => 'Bearer',
        ], 'OAuth login berhasil.');
    }
}
