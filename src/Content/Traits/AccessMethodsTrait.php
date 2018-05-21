<?php

namespace Sportic\Timing\RaceTecClient\Content\Traits;

/**
 * Class AccessMethodsTrait
 * @package Nip\Collections\Traits
 */
trait AccessMethodsTrait
{

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->data = $items;
    }

    /**
     * {@inheritDoc}
     * @param mixed $element
     */
    public function add($element, $key = null)
    {
        if ($key == null) {
            $this->data[] = $element;
            return;
        }
        $this->set($key, $element);
    }

    /**
     * @param string $id
     * @param mixed $value
     */
    public function set($id, $value)
    {
        $this->data[$id] = $value;
    }

    /**
     * Returns a parameter by name.
     *
     * @param string $key The key
     * @param mixed $default The default value if the parameter key does not exist
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
    }

    /**
     * @return boolean
     * @param string $key
     */
    public function has($key)
    {
        return isset($this->data[$key]) || array_key_exists($key, $this->data);
    }

    /**
     * @param $key
     * @return bool
     * @deprecated Use ->has($key) instead
     */
    public function exists($key)
    {
        return $this->has($key);
    }


    /**
     * Returns the parameters.
     *
     * @return array An array of parameters
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * Returns the parameter keys.
     *
     * @return array An array of parameter keys
     */
    public function keys()
    {
        return array_keys($this->data);
    }

    /**
     * Returns the parameter values.
     *
     * @return array An array of parameter values
     */
    public function values()
    {
        return array_values($this->data);
    }


    /**
     * @param string $key
     * @return null
     */
    public function unset($key)
    {
        if (!isset($this->items[$key]) && !array_key_exists($key, $this->data)) {
            return null;
        }
        $removed = $this->data[$key];
        unset($this->data[$key]);
        return $removed;
    }
}
