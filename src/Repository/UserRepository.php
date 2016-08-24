<?php
declare(strict_types=1);

namespace Bacon\Repository;

use Bacon\Entity\User;

interface UserRepository
{
    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param User $user
     * @return void
     */
    public function persist(User $user);
}
