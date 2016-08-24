<?php
declare(strict_types=1);

namespace Bacon\Repository;

use Bacon\Entity\Repository;
use GraphAware\Neo4j\OGM\EntityManager;

final class Neo4jRepositoryRepository implements RepositoryRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Repository[]
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Repository::class)->findAll();
    }

    /**
     * @param Repository $user
     */
    public function persist(Repository $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
