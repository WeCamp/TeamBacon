<?php
declare(strict_types=1);

namespace Bacon\Repository;

use Bacon\Entity\User;
use GraphAware\Neo4j\OGM\EntityManager;

final class Neo4jUserRepository implements UserRepository
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
     * @return User[]
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    /**
     * @param User $user
     */
    public function persist(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
