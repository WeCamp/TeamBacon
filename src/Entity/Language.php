<?php
declare (strict_types = 1);

namespace Bacon\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * @OGM\Node(label="Language")
 */
class Language
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
    private $languageName;

    /**
     * @OGM\Relationship(type="USES", direction="OUTGOING", targetEntity="Bacon\Entity\Repository", collection=true)
     * @var ArrayCollection|Repository[]
     */
    private $repositoriesUsingLanguages;

    public function __construct()
    {
        $this->repositoriesUsingLanguages = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLanguageName(): string
    {
        return $this->languageName;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setLanguageName(string $languageName)
    {
        $this->languageName = $languageName;
    }
}
