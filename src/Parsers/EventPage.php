<?php

namespace Sportic\Timing\RaceTecClient\Parsers;

use DOMElement;
use Sportic\Timing\RaceTecClient\Models\Race;

/**
 * Class EventPage
 * @package Sportic\Timing\RaceTecClient\Parsers
 */
class EventPage extends AbstractParser
{
    /**
     * @return array
     */
    protected function generateContent()
    {
        $return = [];
        $return['races'] = $this->parseRaces();
//        $return = array_merge($return, $this->parseTable());
        return $return;
    }

    public function getModelClassName()
    {
        // TODO: Implement getModelClassName() method.
    }

    protected function parseRaces()
    {
        $return = [];
        $eventMenu = $this->getCrawler()->filter('#ctl00_Content_Main_pnlEventMenu');
        if ($eventMenu->count() > 0) {
            $raceLinks = $eventMenu->filter('div.tab > a');
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

    protected function parseTable()
    {
    }
}
