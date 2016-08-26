<?php
declare (strict_types = 1);

namespace Bacon\Controllers;

use Bacon\Entity\Repository;
use Bacon\Entity\User;
use Bacon\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function usersAction(ServerRequestInterface $request, ResponseInterface $response, array $args)
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
            ->withHeader('Access-Control-Allow-Origin', 'http://127.0.0.1:3000')
            ->withJson($userInformation, 200);
    }

    public function userAction(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $userId = (int) $request->getAttribute('route')->getArgument('userId');

        /** @var User $user */
        $user = $this->userRepository->get($userId);

        $serializedUser = [
            'id' => $user->getId(),
            'userName' => $user->getUsername(),
            'fullName' => $user->getName(),
            'bio' => $user->getBio(),
            'avatar' => $user->getAvatar(),
            'repositoriesOwned' => array_map(
                function (Repository $repository) {
                    return [
                        'id' => $repository->getId(),
                        'repositoryId' => $repository->getRepositoryId(),
                        'name' => $repository->getName(),
                        'fullName' => $repository->getFullName(),
                        'blog' => $repository->getBlog(),
                        'description' => $repository->getDescription(),
                        'url' => $repository->getUrl(),
                    ];
                },
                $user->getRepositoryOwns()->toArray()
            ),
            'repositoriesSubscribedTo' => array_map(
                function (Repository $repository) {
                    return [
                        'id' => $repository->getId(),
                        'repositoryId' => $repository->getRepositoryId(),
                        'name' => $repository->getName(),
                        'fullName' => $repository->getFullName(),
                        'blog' => $repository->getBlog(),
                        'description' => $repository->getDescription(),
                        'url' => $repository->getUrl(),
                    ];
                },
                $user->getRepositoriesSubscribedTo()->toArray()
            ),
            'repositoriesStarred' => array_map(
                function (Repository $repository) {
                    return [
                        'id' => $repository->getId(),
                        'repositoryId' => $repository->getRepositoryId(),
                        'name' => $repository->getName(),
                        'fullName' => $repository->getFullName(),
                        'blog' => $repository->getBlog(),
                        'description' => $repository->getDescription(),
                        'url' => $repository->getUrl(),
                    ];
                },
                $user->getRepositoriesStars()->toArray()
            )
        ];

        return $response->withHeader('Content-type', 'application/json')
            ->withHeader('Access-Control-Allow-Origin', 'http://127.0.0.1:3000')
            ->withJson($serializedUser, 200);
    }
}
