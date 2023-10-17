<?php

namespace pizzashop\auth\api\dto;

class CredentialsDTO extends DTO
{

    public string $email;
    public string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

}