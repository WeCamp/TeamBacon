<?php
declare(strict_types=1);

namespace Bacon\Entity;

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
     * @OGM\Relationship(type="CONTRIBUTES_TO", direction="OUTGOING", targetEntity="Bacon\Entity\Repository", collection=true)
     * @var ArrayCollection|Repository[]
     */
    private $repositoriesContributesTo;

    /**
     * @OGM\Relationship(type="WATCHES", direction="OUTGOING", targetEntity="Bacon\Entity\Repository", collection=true)
     * @var ArrayCollection|Repository[]
     */
    private $repositoriesWatches;

    /**
     * @OGM\Relationship(type="STARS", direction="OUTGOING", targetEntity="Bacon\Entity\Repository", collection=true)
     * @var ArrayCollection|Repository[]
     */
    private $repositoriesStars;

    public function __construct()
    {
        $this->repositoriesContributesTo = new ArrayCollection();
        $this->repositoriesWatches = new ArrayCollection();
        $this->repositoriesStars = new ArrayCollection();
    }

    /**
     * @param Repository $repository
     */
    public function contributeToRepository(Repository $repository)
    {
        if (!$this->repositoriesContributesTo->contains($repository)) {
            $this->repositoriesContributesTo->add($repository);
        }
    }

    /**
     * @param Repository $repository
     */
    public function watchRepository(Repository $repository)
    {
        if (!$this->repositoriesWatches->contains($repository)) {
            $this->repositoriesWatches->add($repository);
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
