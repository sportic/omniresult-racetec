<?php

namespace Sportic\Omniresult\RaceTec\Scrapers;

use Sportic\Omniresult\RaceTec\Parsers\ResultPage as Parser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CompanyPage
 * @package Sportic\Omniresult\RaceTec\Scrapers
 *
 * @method Parser execute()
 */
class ResultPage extends AbstractScraper
{

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->getParameter('uid');
    }

    public function setUid($uid)
    {
        if (strpos($uid, '::')) {
            list($uid, $parameters) = explode('::', $uid);
            $decoded = @base64_decode($parameters);
            $parameters = @unserialize($decoded);
            if (is_array($parameters)) {
                $this->initialize($parameters);
            }
        }
        $this->setParameter('uid', $uid);
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
     * @throws \Sportic\Omniresult\Common\Exception\InvalidRequestException
     */
    protected function doCallValidation()
    {
        $this->validate('uid');
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

        return $crawler;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    public function getCrawlerUri()
    {
        return $this->getCrawlerUriHost()
            . '/myresults.aspx?'
            . 'uid=' . $this->getUid();
    }
}
