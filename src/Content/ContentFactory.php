<?php

namespace Sportic\Timing\RaceTecClient\Content;

/**
 * Class ContentFactory
 * @package Sportic\Timing\RaceTecClient\Content
 */
class ContentFactory
{

    /**
     * @param $array
     * @param string $class
     *
     * @return AbstractContent
     */
    public static function createFromArray($array, $class = GenericContent::class)
    {
        $content = new $class($array);
        return $content;
    }
}