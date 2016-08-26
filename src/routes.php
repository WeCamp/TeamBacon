<?php
declare(strict_types=1);

$app->get('/', 'IndexController:indexAction');
$app->get('/api/users', 'UserController:usersAction');
$app->get('/api/users/{userId}', 'UserController:userAction');
