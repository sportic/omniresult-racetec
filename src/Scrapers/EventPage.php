<?php

namespace Sportic\Omniresult\RaceTec\Scrapers;

use Sportic\Omniresult\RaceTec\Parsers\EventPage as Parser;

/**
 * Class CompanyPage
 * @package Sportic\Omniresult\RaceTec\Scrapers
 *
 * @method Parser execute()
 */
class EventPage extends AbstractScraper
{
    protected $cId;
    protected $rId;
    protected $eId = 1;
    protected $page = 1;

    /**
     * @return mixed
     */
    public function getCId()
    {
        return $this->cId;
    }

    /**
     * @param mixed $cId
     */
    public function setCId($cId)
    {
        $this->cId = $cId;
    }

    /**
     * @return mixed
     */
    public function getRId()
    {
        return $this->rId;
    }

    /**
     * @param mixed $rId
     */
    public function setRId($rId)
    {
        $this->rId = $rId;
    }

    /**
     * @return int
     */
    public function getEId(): int
    {
        return $this->eId;
    }

    /**
     * @param int $eId
     */
    public function setEId(int $eId)
    {
        $this->eId = $eId;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page)
    {
        $this->page = $page;
    }

    /**
     * @inheritdoc
     */
    protected function generateCrawler()
    {
        $client  = $this->getClient();
        $crawler = $client->request(
            'GET',
            $this->getCrawlerUri()
        );

        $cPage = $this->getPage();
        if ($cPage > 1) {
            $link        = $crawler->filter('#ctl00_Content_Main_grdTopPager')->selectLink($this->getPage())->first()->getNode(0);
            $href        = $link->getAttribute('href');
            $eventTarget = str_replace(["javascript:__doPostBack('", "','')"], '', $href);

            $crawler->filter('#__EVENTTARGET')->getNode(0)->setAttribute('value', $eventTarget);

            $form    = $crawler->filter('#aspnetForm')->form();
            $crawler = $client->submit($form);
        }

        return $crawler;
    }

    /**
     * @return string
     */
    public function getCrawlerUri()
    {
        return $this->getCrawlerUriHost().'/Results.aspx?'
               . 'CId=' . $this->getCId()
               . '&RId=' . $this->getRId()
               . '&EId=' . $this->getEId();
    }
}
