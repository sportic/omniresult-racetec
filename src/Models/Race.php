<?php

namespace Sportic\Timing\RaceTecClient\Models;

/**
 * Class Race
 * @package Sportic\Timing\RaceTecClient\Models
 */
class Race extends AbstractModel
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $href;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @param string $href
     */
    public function setHref(string $href)
    {
        $this->href = $href;
    }
}
