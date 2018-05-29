<?php

namespace Sportic\Omniresult\RaceTec\Parsers;

use DOMElement;
use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Helper;
use Sportic\Omniresult\Common\Models\Event;

/**
 * Class EventPage
 * @package Sportic\Omniresult\RaceTec\Parsers
 */
class EventsPage extends AbstractParser
{

    /**
     * @return array
     */
    protected function generateContent()
    {
        $returnContent['records'] = $this->parseEventsTable();
        $returnContent['pagination'] = $this->parseEventsPagination();

        return $returnContent;
    }

    protected function doCallValidation()
    {
    }

    /**
     * @return array
     */
    protected function parseEventsTable()
    {
        $return = [];
        $resultsRows = $this->getCrawler()->filter(
            '#tblAllRaces > tbody > tr'
        );

        if ($resultsRows->count() > 0) {
            foreach ($resultsRows as $resultRow) {
                $result = $this->parseEventsRow($resultRow);
                if ($result) {
                    $return[] = $result;
                }
            }
        }

        return $return;
    }

    /**
     * @param DOMElement $row
     *
     * @return bool|Event
     */
    protected function parseEventsRow(DOMElement $row)
    {
        $parameters = [];
        $parameters['date'] = $row->childNodes[1]->nodeValue;
        $parameters['href'] = $row->childNodes[2]->firstChild->getAttribute('href');
        $parameters['id'] = $this->parseRIdFromHref($parameters['href']);
        $parameters['name'] = $row->childNodes[2]->nodeValue;
        if (count($parameters)) {
            return new Event($parameters);
        }

        return false;
    }

    /**
     * @param $href
     * @return mixed
     */
    protected function parseRIdFromHref($href)
    {
        return Helper::parseParameterFromHref($href, 'RId');
    }

    /**
     * @return array
     */
    protected function parseEventsPagination()
    {
        return [];
    }


    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    protected function getContentClassName()
    {
        return ListContent::class;
    }

    /**
     * @inheritdoc
     */
    public function getModelClassName()
    {
        return Event::class;
    }
}
