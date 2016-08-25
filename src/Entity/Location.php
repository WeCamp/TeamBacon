<?php
declare(strict_types=1);

namespace Bacon\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * @OGM\Node(label="Location")
 */
class Location
{
    /**
     * @OGM\GraphId()
     * @var int
     */
    protected $id;

    /**
     * @OGM\Property(type="string")
     * @var string
     */
    private $location;

    /**
     * @OGM\Relationship(type="HAS", direction="OUTGOING", targetEntity="Bacon\Entity\User", collection=true, mappedBy="location")
     * @var ArrayCollection|User[]
     */
    private $usersInLocation;

    /**
     * @OGM\Relationship(type="HAS", direction="OUTGOING", targetEntity="Bacon\Entity\Organization", collection=true, mappedBy="location")
     * @var ArrayCollection|Organization[]
     */
    private $organizationsInLocation;

    public function __construct()
    {
        $this->usersInLocation = new ArrayCollection();
        $this->organizationsInLocation = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }
}