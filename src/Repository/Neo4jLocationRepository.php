<?php
declare (strict_types = 1);

namespace Bacon\Repository;

use Bacon\Entity\Location;
use GraphAware\Neo4j\OGM\EntityManager;

final class Neo4jLocationRepository implements LocationRepository
{
    /** @var EntityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Location::class)->findAll();
    }

    /**
     * @param Location $location
     * @return void
     */
    public function persist(Location $location)
    {
        $this->entityManager->persist($location);
        $this->entityManager->flush();
    }
}
