<?php

namespace pizzashop\auth\domain\manager;

interface iJwtManager
{
    public function setIssuer(string $issuer): void;
    public function create(array $payload): string;
    public function validate(string $t): array;

}