<?php
declare(strict_types=1);

namespace Bacon\Service\Crawler\Dto;

class Language
{
    protected $language;
    protected $characters;

    /**
     * Language constructor
     * @param $language
     * @param $characters
     */
    public function __construct($language, $characters)
    {
        $this->setLanguage($language)
            ->setCharacters($characters);
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * @param integer $characters
     */
    public function setCharacters(int $characters)
    {
        $this->characters = $characters;

        return $this;
    }
}