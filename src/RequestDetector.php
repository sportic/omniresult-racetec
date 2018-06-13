<?php

namespace Sportic\Omniresult\RaceTec;

use Sportic\Omniresult\Common\RequestDetector\AbstractRequestDetector;

/**
 * Class RequestDetector
 * @package Sportic\Omniresult\RaceTec
 */
class RequestDetector extends AbstractRequestDetector
{

    /**
     * @inheritdoc
     */
    protected function isValidRequest()
    {
        if (in_array(
            $this->getUrlComponent('host'),
            ['cronometraj.racetecresults.com', 'racetecresults.com']
        )) {
            return true;
        }
        return parent::isValidRequest();
    }

    /**
     * @return string
     */
    protected function detectAction()
    {
        $path = strtolower($this->getUrlComponent('path'));
        if ($path == '/results.aspx') {
            return 'results';
        }
        return '';
    }

    /**
     * @inheritdoc
     */
    protected function detectParams()
    {
        parse_str($this->getUrlComponent('query'), $query);

        $return = [];

        $params = ['CId', 'RId', 'EId'];
        foreach ($params as $queryParam) {
            if (isset($query[$queryParam])) {
                $param = str_replace('i', 'I', strtolower($queryParam));
                $return[$param] = $query[$queryParam];
            }
        }

        return $return;
    }
}
