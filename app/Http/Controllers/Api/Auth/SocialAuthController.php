<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialAuthService;
use Illuminate\Http\Request;

class SocialAuthController extends Controller
{
    protected  $socialAuthService;

    public function __construct(SocialAuthService $socialAuthService)
    {
        $this->socialAuthService = $socialAuthService;
    }

    public function handleSocialLogin(Request $request)
    {
        $data = $request->validate([
            'provider' => 'required|in:google',
            'access_token' => 'required|string',
        ]);

        $user = $this->socialAuthService->authenticate(
            $data['provider'],
            $data['access_token']
        );

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid social token',
            ], 401);
        }

        $token = $user->createToken('API TOKEN')->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'Login success',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token,
            ],
        ],200);
    }
}