<?php
declare (strict_types = 1);

namespace Bacon\Controllers;

use Bacon\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct($userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function indexAction(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $users = $this->userRepository->findAll();

        return $response->withHeader('Content-type', 'application/json')
            ->withJson($users, 200);
    }
}
