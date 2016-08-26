<?php
declare(strict_types=1);

namespace Bacon\Controllers;

use Bacon\Entity\User;
use Bacon\Repository\Neo4jUserRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;

final class IndexController
{
    /**
     * @var Neo4jUserRepository
     */
    private $userRepository;

    /**
     * @var PhpRenderer
     */
    private $phpRenderer;

    /**
     * @param Neo4jUserRepository $userRepository
     * @param PhpRenderer $phpRenderer
     */
    public function __construct(Neo4jUserRepository $userRepository, PhpRenderer $phpRenderer)
    {
        $this->userRepository = $userRepository;
        $this->phpRenderer = $phpRenderer;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return mixed
     */
    public function indexAction(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        /** @var User[] $users */
        $users = $this->userRepository->findAll();

        return $this->phpRenderer->render($response, 'index.phtml', ['users' => $users]);
    }
}
