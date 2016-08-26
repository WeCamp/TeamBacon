<?php
declare(strict_types=1);

namespace Bacon\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * @OGM\Node(label="Repository")
 */
class Repository
{
    /**
     * @OGM\GraphId()
     * @var int
     */
    protected $id;

    /**
     * @OGM\Property(type="int")
     * @var integer
     */
    protected $repositoryId;

    /**
     * @OGM\Property(type="string")
     * @var string
     */
    private $name;

    /**
     * @OGM\Property(type="string")
     * @var string
     */
    private $fullName;

    /**
     * @OGM\Property(type="string")
     * @var string
     */
    private $blog;

    /**
     * @OGM\Property(type="string")
     * @var string
     */
    private $description;

    /**
     * @OGM\Property(type="string")
     * @var string
     */
    private $url;

    /**
     * @OGM\Property(type="string")
     * @var string
     */
    private $location;

    /**
     * @OGM\Relationship(type="OWNS", direction="OUTGOING", targetEntity="Bacon\Entity\User", collection=true, mappedBy="usersSubscribedToRepository")
     * @var ArrayCollection|User[]
     */
    private $usersOwnsRepository;

    /**
     * @OGM\Relationship(type="SUBSCRIBES_TO", direction="OUTGOING", targetEntity="Bacon\Entity\User", collection=true, mappedBy="repositoriesWatches")
     * @var ArrayCollection|User[]
     */
    private $usersSubscribedToRepository;

    /**
     * @OGM\Relationship(type="STARS", direction="OUTGOING", targetEntity="Bacon\Entity\User", collection=true, mappedBy="repositoriesStars")
     * @var ArrayCollection|User[]
     */
    private $usersStarRepository;

    /**
     * @OGM\Relationship(type="USES", direction="OUTGOING", targetEntity="Bacon\Entity\Language", collection=true, mappedBy="repositoriesUsingLanguages")
     * @var ArrayCollection|Repository[]
     */
    private $languagesBelongsToRepository;

    /**
     * @param Language $language
     */
    public function useLanguage(Language $language)
    {
        if (!$this->languagesBelongsToRepository->contains($language)) {
            $this->languagesBelongsToRepository->add($language);
        }
    }

    public function __construct()
    {
        $this->usersOwnsRepository = new ArrayCollection();
        $this->usersSubscribedToRepository = new ArrayCollection();
        $this->usersStarRepository = new ArrayCollection();
        $this->languagesBelongsToRepository = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName(string $fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getBlog(): string
    {
        return $this->blog;
    }

    /**
     * @param string $blog
     */
    public function setBlog(string $blog)
    {
        $this->blog = $blog;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location)
    {
        $this->location = $location;
    }

    /**
     * @return int
     */
    public function getRepositoryId(): int
    {
        return $this->repositoryId;
    }

    /**
     * @param int $repositoryId
     */
    public function setRepositoryId(int $repositoryId)
    {
        $this->repositoryId = $repositoryId;
    }

}
