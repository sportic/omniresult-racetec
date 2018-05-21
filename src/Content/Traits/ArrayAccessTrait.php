<?php

namespace Sportic\Timing\RaceTecClient\Content\Traits;

/**
 * Class ArrayAccessTrait
 * @package Nip\Collections
 */
trait ArrayAccessTrait
{

    /**
     * Determine if the given configuration option exists.
     *
     * @param  string $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Get a configuration option.
     *
     * @param  string $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * @param  string $offset
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            $this->add($value);
            return;
        }
        $this->set($offset, $value);
    }

    /**
     * Unset a configuration option.
     *
     * @inheritdoc
     */
    public function offsetUnset($key)
    {
        $this->unset($key);
    }
}
