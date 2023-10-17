<?php

namespace pizzashop\auth\api\manager;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtManager
{
    public string $secret;
    public string $alg;

    public function __construct()
    {
        $this->secret = getenv('JWT_SECRET');
        $this->alg = 'HS512';
    }

    public function create(array $payload): string
    {
        $header = ['alg' => $this->alg,
            'typ' => 'JWT'
        ];

        return JWT::encode($payload, $this->secret, $this->alg);
    }

    public function validate(string $t): void
    {
        try {
            $decoded = JWT::decode($t, new Key($this->secret, $this->alg));
        } catch (\Exception) {
            throw new \Exception("Invalid token");
        }
    }
}