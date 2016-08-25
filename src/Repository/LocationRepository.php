<?php
declare (strict_types = 1);

namespace Bacon\Repository;

use Bacon\Entity\Location;

interface LocationRepository
{
    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param Location $location
     * @return void
     */
    public function persist(Location $location);
}
