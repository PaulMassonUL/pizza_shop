<?php

namespace pizzashop\auth\api\service;

use pizzashop\auth\api\dto\CredentialsDTO;
use pizzashop\auth\api\dto\TokenDTO;
use pizzashop\auth\api\dto\UserDTO;

interface iAuth
{

    public function signup(CredentialsDTO $c) : UserDTO;

    public function signin(CredentialsDTO $c) : TokenDTO;

    public function validate(TokenDTO $t) : UserDTO;

    public function refresh(TokenDTO $t) : TokenDTO;

    public function activate_signup(TokenDTO $t) : void;

    public function reset_password(TokenDTO $t, CredentialsDTO $c, string $newPassword) : void;

}