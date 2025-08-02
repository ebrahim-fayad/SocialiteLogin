<?php

namespace App\Services;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SocialAuthService
{
  public function authenticate($provider, $accessToken)
  {
    try {
      $socialUser = Socialite::driver($provider)
        ->stateless()
        ->userFromToken($accessToken);

      // بندور على مستخدم موجود بنفس provider_id
      $user = User::where('auth_provider', $provider)
        ->where('auth_provider_id', $socialUser->getId())
        ->first();

      // أو بندور عليه بالايميل فقط
      if (!$user) {
        $user = User::where('email', $socialUser->getEmail())->first();
      }

      // لو مش موجود خالص ننشئه
      if (!$user) {
        $user = User::create([
          'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Unknown',
          'email' => $socialUser->getEmail(),
          'auth_provider' => $provider,
          'auth_provider_id' => $socialUser->getId(),
          'password' => Hash::make('Password@1234'),
        ]);
      }

      return $user;

    } catch (\Exception $e) {
      \Log::error('Social login failed: ' . $e->getMessage());
      return null;
    }
  }
}