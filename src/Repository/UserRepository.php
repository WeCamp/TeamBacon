<?php
declare(strict_types=1);

namespace Bacon\Repository;

use Bacon\Entity\User;

interface UserRepository
{
    /**
     * @param int $userId
     * @return User
     */
    public function get(int $userId): User;

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
