<?php
declare(strict_types=1);

namespace Bacon\Repository;

use Bacon\Entity\Organization;

interface OrganizationRepository
{
    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param Organization $user
     * @return void
     */
    public function persist(Organization $user);
}
