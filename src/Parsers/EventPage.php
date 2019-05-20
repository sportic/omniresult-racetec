<?php

namespace Sportic\Omniresult\RaceTec\Parsers;

use DOMElement;
use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Models\Race;
use Sportic\Omniresult\Common\Models\Result;

/**
 * Class EventPage
 * @package Sportic\Omniresult\RaceTec\Parsers
 */
class EventPage extends AbstractParser
{
    protected $returnContent = [];

    /**
     * @return array
     */
    protected function generateContent()
    {
        $this->returnContent['records'] = $this->parseRaces();
        return $this->returnContent;
    }

    /**
     * @return array
     */
    protected function parseRaces()
    {
        $return = [];
        $eventMenu = $this->getCrawler()->filter('#ctl00_Content_Main_divEvents');
        if ($eventMenu->count() > 0) {
            $raceLinks = $eventMenu->filter('li.nav-item > a');
            foreach ($raceLinks as $link) {
                $parameters = [
                    'name' => $link->nodeValue,
                    'href' => $link->getAttribute('href')
                ];
                $return[] = new Race($parameters);
            }
        }

        return $return;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    protected function getContentClassName()
    {
        return ListContent::class;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    public function getModelClassName()
    {
        return Race::class;
    }
}
