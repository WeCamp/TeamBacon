<?php
declare (strict_types = 1);

namespace Bacon\Repository;

use Bacon\Entity\Language;
use GraphAware\Neo4j\OGM\EntityManager;

final class Neo4jLanguageRepository implements LanguageRepository
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
        return $this->entityManager->getRepository(Language::class)->findAll();
    }

    /**
     * @param Language $user
     * @return void
     */
    public function persist(Language $user)
    {
        $this->entityManager->persist($user);
    }

    public function flush()
    {
        $this->entityManager->flush();
    }
}
