<?php
declare(strict_types=1);

namespace Bacon\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;

final class IndexController
{
    /**
     * @var PhpRenderer
     */
    private $phpRenderer;

    /**
     * @param PhpRenderer $phpRenderer
     */
    public function __construct(PhpRenderer $phpRenderer)
    {
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
        return $this->phpRenderer->render($response, 'index.phtml', []);
    }
}
