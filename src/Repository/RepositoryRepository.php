<?php
declare(strict_types=1);

namespace Bacon\Repository;

use Bacon\Entity\Repository;

interface RepositoryRepository
{
    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param Repository $user
     * @return void
     */
    public function persist(Repository $user);
}
