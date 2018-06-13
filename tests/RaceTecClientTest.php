<?php

namespace Sportic\Omniresult\RaceTec\Tests;

use Sportic\Omniresult\Common\RequestDetector\DetectorResult;
use Sportic\Omniresult\RaceTec\RaceTecClient;

/**
 * Class RaceTecClientTest
 * @package Sportic\Omniresult\RaceTec\Tests
 */
class RaceTecClientTest extends AbstractTest
{
    public function testDetectorValidUrl()
    {
        $client = new RaceTecClient();
        self::assertTrue($client->supportsDetect());

        $result = $client->detect('http://cronometraj.racetecresults.com/Results.aspx?CId=16648&RId=2111&EId=1');
        self::assertInstanceOf(DetectorResult::class, $result);
        self::assertTrue($result->isValid());
//        self::assertInstanceOf(RaceTecClient::class, $result->getClient());
        self::assertSame('results', $result->getAction());
        self::assertSame(
            ['cId' => '16648', 'rId' => '2111', 'eId' => '1'],
            $result->getParams()
        );
    }
}
