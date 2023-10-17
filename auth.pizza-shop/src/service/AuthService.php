<?php

namespace pizzashop\auth\api\service;

use pizzashop\auth\api\dto\CredentialsDTO;
use pizzashop\auth\api\dto\TokenDTO;
use pizzashop\auth\api\dto\UserDTO;

class AuthService implements iAuth
{

    public function signup(CredentialsDTO $c): UserDTO
    {
        // TODO: Implement signup() method.
    }

    public function signin(CredentialsDTO $c): TokenDTO
    {
        // TODO: Implement signin() method.
    }

    public function validate(TokenDTO $t): UserDTO
    {
        // TODO: Implement validate() method.
    }

    public function refresh(TokenDTO $t): TokenDTO
    {
        // TODO: Implement refresh() method.
    }

    public function activate_signup(TokenDTO $t): void
    {
        // TODO: Implement activate_signup() method.
    }

    public function reset_password(TokenDTO $t, CredentialsDTO $c): void
    {
        // TODO: Implement reset_password() method.
    }
}