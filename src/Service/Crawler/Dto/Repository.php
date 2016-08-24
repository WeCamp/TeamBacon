<?php
declare(strict_types=1);

namespace Bacon\Service\Crawler\Dto;

class Repository
{
    protected $blog;
    protected $description;
    protected $fullName;
    protected $id;
    protected $lang = [];
    protected $name;
    protected $url;

    static public function createFromJson(string $json)
    {
        $object = json_decode($json);

        return self::createFromObject($object);
    }

    static public function createFromObject(\stdClass $object)
    {
        $organization = new self;
        $organization->setBlog($object->blog)
            ->setDescription($object->description)
            ->setFullName($object->full_name)
            ->setId($object->id)
            ->setName($object->name)
            ->setUrl($object->url);

        return $organization;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
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
    public function getLang(): array
    {
        return $this->lang;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
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
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

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
     * @param array $lang
     */
    public function setLang(array $lang)
    {
        $this->lang = $lang;

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
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

}
