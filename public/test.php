<?php
declare(strict_types=1);

require_once '../vendor/autoload.php';

use Bacon\Config\Config;
use Bacon\Repository\Neo4jUserRepository;
use Bacon\Entity\User;
use Bacon\Repository\Neo4jRepositoryRepository;
use Bacon\Entity\Repository;
use GraphAware\Neo4j\OGM\EntityManager;

$manager = EntityManager::create(Config::CONFIG['neo4jHost']);

$userRepository = new Neo4jUserRepository($manager);
$repositoryRepository = new Neo4jRepositoryRepository($manager);

$repository1 = new Repository();
$repository1->setName('Repository 1');

$repository2 = new Repository();
$repository2->setName('Repository 2');

$user = new User();
$user->setName('User 1');

$user->contributeToRepository($repository1);
$user->watchRepository($repository1);
$user->starRepository($repository1);

$user->watchRepository($repository2);

$userRepository->persist($user);
$repositoryRepository->persist($repository1);

echo 'done';
