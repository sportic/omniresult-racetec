<?php

namespace Sportic\Timing\RaceTecClient\Content;

use ArrayAccess;
use Sportic\Timing\RaceTecClient\Content\Traits\AccessMethodsTrait;
use Sportic\Timing\RaceTecClient\Content\Traits\ArrayAccessTrait;
use Sportic\Timing\RaceTecClient\Helper;

/**
 * Class AbstractContent
 * @package Sportic\Timing\RaceTecClient\Content
 */
abstract class AbstractContent implements ArrayAccess
{
    use ArrayAccessTrait, AccessMethodsTrait;
    protected $data = [];

    /**
     * AbstractContent constructor.
     *
     * @param $parameters
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
                } else {
                    $this->data[$name] = $value;
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function __toArray()
    {
        return Helper::objectToArray($this->data);
    }
}
