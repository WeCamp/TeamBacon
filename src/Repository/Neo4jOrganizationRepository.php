<?php
declare(strict_types=1);

namespace Bacon\Repository;

use Bacon\Entity\Organization;
use GraphAware\Neo4j\OGM\EntityManager;

final class Neo4jOrganizationRepository implements OrganizationRepository
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
     * @return Organization[]
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Organization::class)->findAll();
    }

    /**
     *
     *
     * @param $key
     * @param $value
     * @return null|object
     */
    public function findOneBy($key, $value)
    {
        return $this->entityManager->getRepository(Repository::class)->findOneBy($key, $value);
    }

    /**
     * @param Organization $user
     */
    public function persist(Organization $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
