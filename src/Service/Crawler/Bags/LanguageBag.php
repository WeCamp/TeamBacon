<?php
declare(strict_types=1);

namespace Bacon\Service\Crawler\Bags;


use Bacon\Service\Crawler\Dto\Language;

class LanguageBag extends BaseBag
{
    public function __construct(array $items)
    {
        foreach ($items as $key => $item)
        {
            if (!$item instanceof Language)
            {
                throw new \Exception('Only Language objects can be added to the LanguageBag');
            }
            $this->items[$key] = $item;
        }
    }

    /**
     * Add a message to the bag.
     *
     * @param  string  $key
     * @param  string  $message
     * @return $this
     */
    public function add($item)
    {
        if (!$item instanceof Language)
        {
            throw new \Exception('Only Language objects can be added to the LanguageBag');
        }

        return parent::add($item);
    }
}