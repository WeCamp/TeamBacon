<?php
declare(strict_types=1);

namespace Bacon\Entity;

use Bacon\Entity\Repository;
use Doctrine\Common\Collections\ArrayCollection;
use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * @OGM\Node(label="User")
 */
class User
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
    private $name;

    /**
     * @OGM\Property(type="string")
     * @var string
     */
    private $username;

    /**
     * @OGM\Property(type="string")
     * @var string
     */
    private $bio;

    /**
     * @OGM\Property(type="string")
     * @var string
     */
    private $avatar;

    /**
     * @OGM\Relationship(type="OWNS", direction="OUTGOING", targetEntity="Bacon\Entity\Repository", collection=true)
     * @var ArrayCollection|Repository[]
     */
    private $repositoriesOwns;

    /**
     * @OGM\Relationship(type="SUBSCRIBES_TO", direction="OUTGOING", targetEntity="Bacon\Entity\Repository", collection=true)
     * @var ArrayCollection|Repository[]
     */
    private $repositoriesSubscribedTo;

    /**
     * @OGM\Relationship(type="STARS", direction="OUTGOING", targetEntity="Bacon\Entity\Repository", collection=true)
     * @var ArrayCollection|Repository[]
     */
    private $repositoriesStars;

    /**
     * @OGM\Relationship(type="IS_LOCATED_IN", direction="OUTGOING", targetEntity="Bacon\Entity\Location", collection=false)
     * @var Location
     */
    private $location = null;

    public function __construct()
    {
        $this->repositoriesOwns = new ArrayCollection();
        $this->repositoriesSubscribedTo = new ArrayCollection();
        $this->repositoriesStars = new ArrayCollection();
    }

    /**
     * @param Repository $repository
     */
    public function ownsRepository(Repository $repository)
    {
        if (!$this->repositoriesOwns->contains($repository)) {
            $this->repositoriesOwns->add($repository);
        }
    }

    /**
     * @param Repository $repository
     */
    public function watchRepository(Repository $repository)
    {
        if (!$this->repositoriesSubscribedTo->contains($repository)) {
            $this->repositoriesSubscribedTo->add($repository);
        }
    }

    /**
     * @param Repository $repository
     */
    public function starRepository(Repository $repository)
    {
        if (!$this->repositoriesStars->contains($repository)) {
            $this->repositoriesStars->add($repository);
        }
    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation(Location $location)
    {
        $this->location = $location;
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
    public function getBio(): string
    {
        return $this->bio;
    }

    /**
     * @param string $bio
     */
    public function setBio(string $bio)
    {
        $this->bio = $bio;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(string $avatar)
    {
        $this->avatar = $avatar;
    }


    /**
     * @return ArrayCollection|Repository[]
     */
    public function getRepositoriesSubscribedTo(): ArrayCollection
    {
        return $this->repositoriesSubscribedTo;
    }

    /**
     * @return ArrayCollection|Repository[]
     */
    public function getRepositoryOwns(): ArrayCollection
    {
        return $this->repositoriesOwns;
    }

    /**
     * @return ArrayCollection|Repository[]
     */
    public function getRepositoriesStars(): ArrayCollection
    {
        return $this->repositoriesStars;
    }

    /**
     * Check the unique property in order to prevent creating the ode twice
     *
     * @param User $user
     * @return bool
     */
    public function isEqualTo(User $user)
    {
        if ($user->getUsername() === $this->getUsername()) {
            return true;
        }

        return false;
    }
}
