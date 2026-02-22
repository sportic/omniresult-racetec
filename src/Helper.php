<?php

namespace Sportic\Omniresult\RaceTec;

/**
 * Class Helper
 * @package Sportic\Omniresult\RaceTec
 */
class Helper extends \Sportic\Omniresult\Common\Helper
{

    public static function statusParse(?string $status): string
    {
        $status = trim($status);
        $status = strtolower($status);
        return $status;
    }
}
