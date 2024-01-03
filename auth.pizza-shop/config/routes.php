<?php
declare(strict_types=1);

use pizzashop\auth\app\actions\SigninAction;

return function (\Slim\App $app): void {

    $app->post('/api/users/signin[/]', SigninAction::class)->setName('signin');

    $app->get('/api/users/validate[/]', \pizzashop\auth\app\actions\ValidateTokenAction::class)->setName('validate');

};