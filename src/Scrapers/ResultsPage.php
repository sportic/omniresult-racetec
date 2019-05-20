<?php

namespace Sportic\Omniresult\RaceTec\Scrapers;

use Sportic\Omniresult\RaceTec\Parsers\EventPage as Parser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CompanyPage
 * @package Sportic\Omniresult\RaceTec\Scrapers
 *
 * @method Parser execute()
 */
class ResultsPage extends AbstractScraper
{
    /**
     * @return mixed
     */
    public function getCId()
    {
        return $this->getParameter('cId');
    }

    /**
     * @return mixed
     */
    public function getRId()
    {
        return $this->getParameter('rId');
    }

    /**
     * @return int
     */
    public function getEId()
    {
        return $this->getParameter('eId');
    }

    /**
     * @return boolean
     */
    public function getGenderCategoryMerge()
    {
        return $this->getParameter('genderCategoryMerge', false);
    }

    /**
     * @return boolean
     */
    public function isGenderCategoryMerge()
    {
        return $this->getGenderCategoryMerge() === true || $this->getGenderCategoryMerge() == 1;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->getParameter('page', 1);
    }

    /**
     * @inheritdoc
     */
    protected function generateCrawler()
    {
        $client = $this->getClient();
        $crawler = $client->request(
            'GET',
            $this->getCrawlerUri()
        );

        if ($this->getPage() > 1) {
            $crawler = $this->goToPage($client, $crawler);
        }

        return $crawler;
    }

    /**
     * @param $client
     * @param $crawler
     * @return mixed
     */
    protected function goToPage($client, $crawler)
    {
        $rPage = $this->getPage();
        if ($rPage <= 7) {
            return $this->clickPageLink($client, $crawler, $rPage);
        }
        $cPage = 7;
        while ($cPage <= $rPage) {
            $crawler = $this->clickPageLink($client, $crawler, $cPage);
            if (($rPage - $cPage) <= 2) {
                return $this->clickPageLink($client, $crawler, $rPage);
            } else {
                $cPage = $cPage + 2;
            }
        }
        return $crawler;
    }

    /**
     * @param $client
     * @param Crawler $crawler
     * @return mixed
     */
    protected function clickPageLink($client, $crawler, $page)
    {
        $pagerContent = $crawler->filter('#ctl00_Content_Main_divTopPager');
        $link = $pagerContent->selectLink($page)->first()->getNode(0);
        $href = $link->getAttribute('href');
        $eventTarget = str_replace(["javascript:__doPostBack('", "','')"], '', $href);

        $crawler->filter('#__EVENTTARGET')->getNode(0)->setAttribute('value', $eventTarget);

        $form = $crawler->filter('#aspnetForm')->form();
        $crawler = $client->submit($form);
        return $crawler;
    }

    /**
     * @return string
     */
    public function getCrawlerUri()
    {
        return $this->getCrawlerUriHost() . '/Results.aspx?'
            . 'CId=' . $this->getCId()
            . '&RId=' . $this->getRId()
            . '&EId=' . $this->getEId()
            . '&dt=0';
    }
}
