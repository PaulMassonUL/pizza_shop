<?php

namespace pizzashop\gateway\domain\service;

use Psr\Http\Message\ResponseInterface;

interface iGateway
{

    public function request(string $method, string $uri = '', array $options = []) : ResponseInterface;

}