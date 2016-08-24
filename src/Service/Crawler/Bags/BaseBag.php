<?php
declare(strict_types=1);

namespace Bacon\Service\Crawler\Bags;


class BaseBag implements Iterator
{
    protected $items = [];

    /**
     * Get the keys present in the message bag.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->items);
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
        if ($this->isUnique($key, $item)) {
            $this->items[$key] = $item;
        }
        return $this;
    }

    /**
     * Determine if a key and message combination already exists.
     *
     * @param  string  $key
     * @param  string  $message
     * @return bool
     */
    protected function isUnique($key, $item)
    {
        $items = (array) $this->items;
        return ! isset($items[$key]) || ! in_array($item, $items[$key]);
    }

    /**
     * Determine if messages exist for all of the given keys.
     *
     * @param  array|string  $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->items[$key]);
    }

    /**
     * Determine if messages exist for any of the given keys.
     *
     * @param  array  $keys
     * @return bool
     */
    public function hasAny($keys = [])
    {
        foreach ($keys as $key) {
            if ($this->has($key)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the first message from the bag.
     *
     * @param  string  $key
     * @return string
     */
    public function first()
    {
        return array_values($this->items)[0];
    }

    /**
     * Get all of the messages from the bag for a given key.
     *
     * @param  string  $key
     * @return array
     */
    public function get($key)
    {
        if ($this->has($key))
        {
            return $this->items[$key];
        }

        return false;
    }

    /**
     * Get all of the messages for every key in the bag.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Get all of the unique messages for every key in the bag.
     *
     * @return array
     */
    public function unique()
    {
        return array_unique($this->items);
    }

    /**
     * Determine if the message bag has any messages.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * Get the number of messages in the container.
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Determine if the message bag has any messages.
     *
     * @return bool
     */
    public function any()
    {
        return $this->count() > 0;
    }

    public function rewind()
    {
        reset($this->var);
    }

    public function current()
    {
        return current($this->var);
    }

    public function key()
    {
        return key($this->var);
    }

    public function next()
    {
        return next($this->item);
    }

    public function valid()
    {
        $key = key($this->item);
        return ($key !== NULL && $key !== FALSE);
    }
}