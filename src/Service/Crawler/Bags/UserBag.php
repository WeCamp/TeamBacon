<?php
declare(strict_types=1);

namespace Bacon\Service\Crawler\Bags;


use Bacon\Service\Crawler\Dto\User;

class UserBag extends BaseBag
{
    public function __construct(array $items)
    {
        foreach ($items as $key => $item)
        {
            if (!$item instanceof User)
            {
                throw new \Exception('Only User objects can be added to the UserBag');
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
    public function add($key, $item)
    {
        if (!$item instanceof User)
        {
            throw new \Exception('Only User objects can be added to the UserBag');
        }

        return parent::add($key, $item);
    }
}