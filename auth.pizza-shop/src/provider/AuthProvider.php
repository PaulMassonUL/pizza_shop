<?php

namespace pizzashop\auth\api\provider;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use pizzashop\auth\api\entities\User;

class AuthProvider
{

    public function checkCredentials(string $user, string $pass): void
    {
        try {
            $user = User::where('email', $user)->firstOrFail();
            if (!password_verify($pass, $user->password)) throw new \Exception("Invalid password");
        } catch (\Exception) {
            throw new AuthProviderInvalidCredentialsException("Invalid credentials");
        }
    }

    public function checkToken(string $token): void
    {
        try {
            User::where('refresh_token', $token)->firstOrFail();
        } catch (\Exception) {
            throw new AuthProviderInvalidTokenException("Invalid refresh token");
        }
    }

    public function register(string $user, string $pass): void
    {
        try {
            User::where('email', $user)->firstOrFail();
            throw new AuthProviderInvalidCredentialsException("User already exists");
        } catch (ModelNotFoundException) {
            $user = new User();
            $user->email = $user;
            $user->password = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);
            $user->save();
        }
    }

    public function activate(string $token): void
    {
        try {
            $user = User::where('activation_token', $token)
                ->where('activation_token_expiration_date', '>=', date('Y-m-d H:i:s'))
                ->firstOrFail();

            $user->active = true;
            $user->save();
        } catch (ModelNotFoundException) {
            throw new AuthProviderInvalidTokenException("Invalid activation token");
        }
    }

    public function resetPassword(string $token, string $old_pass, string $new_pass): void
    {
        try {
            $user = User::where('reset_passwd_token', $token)
                ->where('reset_passwd_token_expiration_date', '>=', date('Y-m-d H:i:s'))
                ->firstOrFail();

            if (!password_verify($old_pass, $user->password)) throw new AuthProviderInvalidCredentialsException("Invalid password");

            $user->password = password_hash($new_pass, PASSWORD_DEFAULT, ['cost' => 12]);
            $user->save();
        } catch (ModelNotFoundException) {
            throw new AuthProviderInvalidTokenException("Invalid reset password token");
        }
    }
}