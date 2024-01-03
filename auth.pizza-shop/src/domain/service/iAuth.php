<?php

namespace pizzashop\auth\domain\service;

use pizzashop\auth\domain\dto\CredentialsDTO;
use pizzashop\auth\domain\dto\TokenDTO;
use pizzashop\auth\domain\dto\UserDTO;

interface iAuth
{

    public function signup(CredentialsDTO $c) : UserDTO;

    public function signin(CredentialsDTO $c) : TokenDTO;

    public function validate(TokenDTO $t) : UserDTO;

    public function refresh(TokenDTO $t) : TokenDTO;

    public function activate_signup(TokenDTO $t) : void;

    public function reset_password(TokenDTO $t, CredentialsDTO $c, string $newPassword) : void;

}