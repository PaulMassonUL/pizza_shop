<?php
declare(strict_types=1);

use pizzashop\auth\app\actions\SigninAction;
use pizzashop\auth\app\actions\SignupAction;
use pizzashop\auth\app\actions\ValidateAction;
use pizzashop\auth\app\actions\RefreshAction;

return function (\Slim\App $app): void {

    $app->post('/api/users/signin[/]', SigninAction::class)->setName('signin');

    $app->post('/api/users/signup[/]', SignupAction::class)->setName('signup');

    $app->get('/api/users/validate[/]', ValidateAction::class)->setName('validate');

    $app->post('/api/users/refresh[/]', RefreshAction::class)->setName('refresh');
    
};