<?php
declare(strict_types=1);

namespace Bacon\Service\Crawler\Bags;


use Bacon\Service\Crawler\Dto\Repository;

class UserBag extends BaseBag
{
    public function __construct(array $items)
    {
        foreach ($items as $key => $item)
        {
            if (!$item instanceof Repository)
            {
                throw new \Exception('Only Repository objects can be added to the RepositoryBag');
            }
            $this->items[$key] = $item;
        }
    }

    public function add($key, $item)
    {
        if (!$item instanceof Repository)
        {
            throw new \Exception('Only Repository objects can be added to the RepositoryBag');
        }

        return parent::add($key, $item);
    }
}