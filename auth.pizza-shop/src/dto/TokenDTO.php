<?php

namespace pizzashop\auth\api\dto;

class TokenDTO extends DTO
{

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
    
}