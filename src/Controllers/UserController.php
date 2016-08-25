<?php
declare (strict_types = 1);

namespace Bacon\Controllers;

use Bacon\Entity\User;
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
        /** @var User[] $users */
        $users = $this->userRepository->findAll();

        $userInformation = array_map(
            function (User $user) {
                return [
                    'id' => $user->getId(),
                    'userName' => $user->getUsername(),
                    'fullName' => $user->getName(),
                    'avatar' => $user->getAvatar()
                ];
            },
            $users
        );

        return $response->withHeader('Content-type', 'application/json')
            ->withJson($userInformation, 200);
    }
}
