<?php

namespace pizzashop\auth\api\dto;

class UserDTO extends DTO
{

    public CredentialsDTO $credentials;
    public int $active;
    public string $activation_token;
    public string $activation_token_expiration_date;
    public string $refresh_token;
    public string $refresh_token_expiration_date;
    public string $reset_passwd_token;
    public string $reset_passwd_token_expiration_date;
    public string $username;

    public function __construct(CredentialsDTO $credentials)
    {
        $this->credentials = $credentials;
    }

}