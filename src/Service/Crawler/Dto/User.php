<?php
declare(strict_types=1);

namespace Bacon\Service\Crawler\Dto;

class User
{
    protected $bio;
    protected $blog;
    protected $id;
    protected $followers = [];
    protected $following = [];
    protected $location;
    protected $login;
    protected $name;
    protected $repos = [];
    protected $starred = [];
    protected $subscriptions = [];
    protected $url;

    static public function createFromJson(string $json)
    {
        $object = json_decode($json);

        return self::createFromObject($object);
    }

    static public function createFromObject(\stdClass $object)
    {
        $user = new self;
        $user->setBio($object->bio)
            ->setBlog($object->blog)
            ->setId($object->id)
            ->setLocation($object->location)
            ->setLogin($object->login)
            ->setName($object->name)
            ->setUrl($object->url);

        return $user;
    }

    /**
     * @return mixed
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @return mixed
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getFollowers(): array
    {
        return $this->followers;
    }

    /**
     * @return array
     */
    public function getFollowing(): array
    {
        return $this->following;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getRepos(): array
    {
        return $this->repos;
    }

    /**
     * @return array
     */
    public function getStarred(): array
    {
        return $this->starred;
    }

    /**
     * @return array
     */
    public function getSubscriptions(): array
    {
        return $this->subscriptions;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $bio
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * @param mixed $blog
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;

        return $this;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param array $followers
     */
    public function setFollowers(array $followers)
    {
        $this->followers = $followers;

        return $this;
    }

    /**
     * @param array $following
     */
    public function setFollowing(array $following)
    {
        $this->following = $following;

        return $this;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param array $repos
     */
    public function setRepos(array $repos)
    {
        $this->repos = $repos;

        return $this;
    }

    /**
     * @param array $starred
     */
    public function setStarred(array $starred)
    {
        $this->starred = $starred;

        return $this;
    }

    /**
     * @param array $subscriptions
     */
    public function setSubscriptions(array $subscriptions)
    {
        $this->subscriptions = $subscriptions;

        return $this;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }


}
