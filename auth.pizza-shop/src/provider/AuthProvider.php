<?php

namespace pizzashop\auth\api\provider;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use pizzashop\auth\api\dto\UserDTO;
use pizzashop\auth\api\entities\User;

class AuthProvider
{

    private User $authenticatedUser;

    /**
     * @throws AuthProviderInvalidCredentialsException
     */
    public function checkCredentials(string $user, string $pass): void
    {
        try {
            $user = User::where('email', $user)->firstOrFail();
            if (!password_verify($pass, $user->password)) throw new \Exception("Invalid password");
            $this->authenticatedUser = $user;
            $this->generateRefreshToken($user);
        } catch (\Exception) {
            throw new AuthProviderInvalidCredentialsException("Invalid credentials");
        }
    }

    public function checkToken(string $token): void
    {
        try {
            $user = User::where('refresh_token', $token)->where('refresh_token_expiration_date', '>=', date('Y-m-d H:i:s'))->firstOrFail();
        } catch (\Exception) {
            throw new AuthProviderInvalidTokenException("Invalid refresh token");
        }
        $this->generateRefreshToken($user);
        $this->authenticatedUser = $user;
    }

    public function generateRefreshToken(User $user): void{
        $user->refresh_token = bin2hex(random_bytes(32));
        $user->refresh_token_expiration_date = date('Y-m-d H:i:s', time() + 3600 * 24);
        $user->save();
    }

    /**
     * @throws AuthProviderInvalidCredentialsException
     */
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

    /**
     * @throws AuthProviderInvalidCredentialsException
     * @throws AuthProviderInvalidTokenException
     */
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

    public function getAuthenticatedUser(): array
    {
        return [
            "email" => $this->authenticatedUser->email,
            "username" => $this->authenticatedUser->username,
            "refresh_token" => $this->authenticatedUser->refresh_token
        ];
    }
}