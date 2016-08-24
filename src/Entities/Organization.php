<?php
declare(strict_types=1);

namespace Bacon\Entities;

use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * @OGM\Node(label="Organization")
 */
final class Organization
{
    /**
     * @OGM\GraphId()
     */
    protected $id;

    /**
     * @OGM\Property(type="string")
     */
    private $blog;

    /**
     * @OGM\Property(type="string")
     */
    private $description;
}
