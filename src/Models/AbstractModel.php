<?php

namespace Sportic\Timing\RaceTecClient\Models;

use Sportic\Timing\RaceTecClient\Helper;

/**
 * Class AbstractModel
 * @package Sportic\Timing\RaceTecClient\Models
 */
abstract class AbstractModel
{
    /**
     * AbstractModel constructor.
     *
     * @param array $parameters
     */
    public function __construct($parameters = [])
    {
        $this->setParameters($parameters);
    }

    /**
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        if (is_array($parameters)) {
            foreach ($parameters as $name => $value) {
                $method = 'set' . ucfirst(Helper::camelCase($name));
                if (method_exists($this, $method)) {
                    $this->$method($value);
                } elseif (property_exists($this, $name)) {
                    $this->{$name} = $value;
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function __toArray()
    {
        return call_user_func('get_object_vars', $this);
    }
}
