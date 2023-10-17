<?php

namespace pizzashop\auth\api\manager;

interface iJwtManager
{
    public function create(array $payload): string;
    public function validate(string $t): array;

}