<?php

namespace Sportic\Timing\RaceTecClient\Models;

/**
 * Class Split
 * @package Sportic\Timing\RaceTecClient\Models
 */
class Split extends AbstractModel
{
    protected $name;
    protected $time;
    protected $timeFromStart;

    /**
     * @return array
     */
    public static function getLabelMaps()
    {
        return [
            'name' => 'Split Name',
            'timeFromStart' => 'Time',
            'time' => 'Time From Previous Split',
        ];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return mixed
     */
    public function getTimeFromStart()
    {
        return $this->timeFromStart;
    }
}
