<?php
declare (strict_types = 1);

namespace Bacon\Repository;

use Bacon\Entity\Language;

interface LanguageRepository
{
    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param Language $user
     * @return void
     */
    public function persist(Language $user);
}
