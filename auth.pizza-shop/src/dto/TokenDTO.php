<?php

namespace pizzashop\auth\api\dto;

class TokenDTO extends DTO
{

    public string $refresh_token;
    public string $access_token;

    public function __construct(string $access_token, string $refresh_token)
    {
        $this->refresh_token = $refresh_token;
        $this->access_token = $access_token;
    }

    
}