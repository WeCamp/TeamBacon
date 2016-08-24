<?php
declare (strict_types = 1);

namespace Bacon\Controllers;

final class UserController
{
    public function indexAction($request, $response, array $args)
    {
        echo 'Hello world from usercontroller';
    }
}
