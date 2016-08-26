<?php

$app->get('/api/users', 'UserController:usersAction');
$app->get('/api/users/{userId}', 'UserController:userAction');
