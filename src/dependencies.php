<?php

use Bacon\Config\Config;
use Bacon\Controllers\IndexController;
use Bacon\Controllers\UserController;
use Bacon\Repository\Neo4jOrganizationRepository;
use Bacon\Repository\Neo4jUserRepository;
use GraphAware\Neo4j\OGM\EntityManager;
use Slim\Views\PhpRenderer;

$config = Config::get();

$container = $app->getContainer();

// View renderer
$container['Renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// Monolog
$container['Logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Neo4j
$container['EntityManager'] = function ($c) use ($config) {
    return EntityManager::create($config['neo4jHost']);
};

// View
$container['view'] = new PhpRenderer(__DIR__ . '/templates/');

//Repositories
$container['Neo4jOrganisationRepository'] = function ($c) {
    return new Neo4jOrganizationRepository($c['EntityManager']);
};

$container['Neo4jUserRepository'] = function ($c) {
    return new Neo4jUserRepository($c['EntityManager']);
};

// Controllers
$container['IndexController'] = function ($c) {
    return new IndexController($c['view']);
};

$container['UserController'] = function ($c) {
    return new UserController($c['Neo4jUserRepository']);
};
