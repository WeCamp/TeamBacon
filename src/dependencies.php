<?php

use Bacon\Config\Config;
use Bacon\Controllers\UserController;
use Bacon\Repository\Neo4jOrganizationRepository;
use Bacon\Repository\Neo4jUserRepository;
use GraphAware\Neo4j\OGM\EntityManager;

$container = $app->getContainer();

// view renderer
$container['Renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['Logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['EntityManager'] = function ($c) {
    return EntityManager::create(Config::CONFIG['neo4jHost']);
};

$container['Neo4jOrganisationRepository'] = function ($c) {
    return new Neo4jOrganizationRepository($c['EntityManager']);
};

$container['Neo4jUserRepository'] = function ($c) {
    return new Neo4jUserRepository($c['EntityManager']);
};

$container['UserController'] = function ($c) {
    return new UserController($c['Neo4jUserRepository']);
};

