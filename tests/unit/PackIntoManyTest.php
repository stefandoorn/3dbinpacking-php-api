<?php namespace BinPacking3d\Tests;

use BinPacking3d\PackIntoMany;
use BinPacking3d\Query;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class PackIntoManyTest extends BinPackingTestBase
{

    public function testPackIntoMany()
    {
        // Build packing request
        $request = new \BinPacking3d\Entity\Request();

        $bin = new \BinPacking3d\Entity\Bin();
        $bin->setWidth(100);
        $bin->setHeight(120);
        $bin->setDepth(130);
        $bin->setMaxWeight(10);
        $bin->setOuterWidth(110);
        $bin->setOuterHeight(130);
        $bin->setOuterDepth(140);
        $bin->setWeight(0.1);
        $bin->setIdentifier('Test');
        $bin->setInternalIdentifier(1);
        $request->addBin($bin);

        // Item
        $item = new \BinPacking3d\Entity\Item();
        $item->setWidth(50);
        $item->setHeight(60);
        $item->setDepth(70);
        $item->setWeight(5);
        $item->setItemIdentifier('Test');
        $item->setProduct(['product_id' => 1]);
        $request->addItem($item);

        // Set extra info
        $request->setApiKey('API KEY');
        $request->setUsername('USERNAME');

        // Test request
        $this->assertInstanceOf('\BinPacking3d\Entity\Request', $request);

        // Build object
        $packIntoMany = new PackIntoMany($request);
        $this->assertInstanceOf('\BinPacking3d\PackIntoMany', $packIntoMany);

        // Test params
        $this->assertEquals(
            [
                'images_background_color' => '255,255,255',
                'images_bin_border_color' => '59,59,59',
                'images_bin_fill_color' => '230,230,230',
                'images_item_border_color' => '214,79,79',
                'images_item_fill_color' => '177,14,14',
                'images_item_back_border_color' => '215,103,103',
                'images_sbs_last_item_fill_color' => '99,93,93',
                'images_sbs_last_item_border_color' => '145,133,133',
                'images_width' => 250,
                'images_height' => 250,
                'images_source' => 'base64',
                'images_sbs' => 1,
                'stats' => 1,
                'item_coordinates' => 1,
                'images_complete' => 1,
                'images_separated' => 1,
            ], $packIntoMany->getParams()
        );

        // Test get logger without logger
        $this->assertInstanceOf('\Psr\Log\NullLogger', $packIntoMany->getLogger());

        // Test set logger with logger & result
        $result = $packIntoMany->setLogger(new \Psr\Log\NullLogger());
        $this->assertInstanceOf('\BinPacking3d\PackIntoMany', $result);
        $this->assertInstanceOf('\Psr\\Log\LoggerInterface', $packIntoMany->getLogger());
        $this->assertInstanceOf('\Psr\Log\NullLogger', $packIntoMany->getLogger());
    }

    public function testGetClient()
    {
        $request = new \BinPacking3d\Entity\Request();
        $packIntoMany = new PackIntoMany($request);
        $this->assertInstanceOf('\GuzzleHttp\Client', $packIntoMany->getClient());

        $client = new \GuzzleHttp\Client(
            [
                'base_uri' => 'http://test',
                'timeout' => 2.0,
            ]
        );
        $packIntoMany->setClient($client);
        $this->assertInstanceOf('\GuzzleHttp\Client', $packIntoMany->getClient());
    }

    public function testGetRegion()
    {
        $request = new \BinPacking3d\Entity\Request();
        $packIntoMany = new PackIntoMany($request, [], Query::REGION_EU);
        $this->assertEquals('EU', $packIntoMany->getRegion());
        $packIntoMany->setRegion(Query::REGION_US);
        $this->assertEquals('US', $packIntoMany->getRegion());
        $packIntoMany->setRegion(null);
        $this->assertNull($packIntoMany->getRegion());

        $url = $packIntoMany->getUrl(null);
        $this->assertEquals($url, $packIntoMany->getUrl(Query::REGION_GLOBAL));
    }

    public function testCacheTtl()
    {
        $request = new \BinPacking3d\Entity\Request();
        $packIntoMany = new PackIntoMany($request, [], Query::REGION_EU);
        $this->assertEquals(3600, $packIntoMany->getCacheTtl());
        $packIntoMany->setCacheTtl(8400);
        $this->assertEquals(8400, $packIntoMany->getCacheTtl());
    }

    public function testSetCacheDriver()
    {
        $cacheDriver = new \Doctrine\Common\Cache\ArrayCache();
        $request = new \BinPacking3d\Entity\Request();
        $packIntoMany = new PackIntoMany($request, [], Query::REGION_EU);
        $this->assertNull($packIntoMany->getCache());
        $packIntoMany->setCache($cacheDriver);
        $this->assertInstanceOf('\Doctrine\Common\Cache\Cache', $packIntoMany->getCache());
        $this->assertInstanceOf('\Doctrine\Common\Cache\ArrayCache', $packIntoMany->getCache());
    }

    public function testCorrectCachePackIntoManyRequest()
    {
        // Build a fake request
        // Build packing request
        $request = new \BinPacking3d\Entity\Request();

        $bin = new \BinPacking3d\Entity\Bin();
        $bin->setWidth(100);
        $bin->setHeight(120);
        $bin->setDepth(130);
        $bin->setMaxWeight(10);
        $bin->setOuterWidth(110);
        $bin->setOuterHeight(130);
        $bin->setOuterDepth(140);
        $bin->setWeight(0.1);
        $bin->setIdentifier('Test');
        $bin->setInternalIdentifier(1);
        $request->addBin($bin);

        // Item
        $item = new \BinPacking3d\Entity\Item();
        $item->setWidth(50);
        $item->setHeight(60);
        $item->setDepth(70);
        $item->setWeight(5);
        $item->setItemIdentifier('Test');
        $item->setProduct(['product_id' => 1]);
        $request->addItem($item);

        // Set extra info
        $request->setApiKey('API KEY');
        $request->setUsername('USERNAME');

        $packIntoMany = new PackIntoMany($request);
        $cacheDriver = new \Doctrine\Common\Cache\ArrayCache();
        $packIntoMany->setCache($cacheDriver);
        $this->assertInstanceOf('\BinPacking3d\PackIntoMany', $packIntoMany);
        $this->assertInstanceOf('\BinPacking3d\Query', $packIntoMany);

        // Build response mock stack
        $mock = new MockHandler(
            [
                new Response(
                    200, ['Content-Type' => 'application/json'],
                    file_get_contents($this->getFilePath('responses/PackIntoMany/correct.json'))
                ),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // Get response
        $response = $client->get('/PackIntoMany');

        // Tests
        $this->assertEquals(200, $response->getStatusCode());

        // Get parsed JSON request
        $this->assertJsonStringEqualsJsonFile(
            $this->getFilePath('requests/PackIntoMany/request.json'),
            $packIntoMany->renderRequestJson()
        );

        // Get response and test it
        $response = $packIntoMany->handleResponse($response);
        $this->assertInstanceOf('\BinPacking3d\Entity\Packed', $response);
        $this->assertEquals(1, $response->count());
        $this->assertCount(1, $response->getBins());
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $response->getBins()[0]);

        // Run it again to use cache
        // Build response mock stack
        $mock = new MockHandler(
            [
                new Response(
                    200, ['Content-Type' => 'application/json'],
                    file_get_contents($this->getFilePath('responses/PackIntoMany/correct.json'))
                ),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // Get response
        $response = $client->get('/PackIntoMany');

        // Tests
        $this->assertEquals(200, $response->getStatusCode());

        // Get parsed JSON request
        $this->assertJsonStringEqualsJsonFile(
            $this->getFilePath('requests/PackIntoMany/request.json'),
            $packIntoMany->renderRequestJson()
        );

        // Get response and test it
        $response = $packIntoMany->handleResponse($response);
        $this->assertInstanceOf('\BinPacking3d\Entity\Packed', $response);
        $this->assertEquals(1, $response->count());
        $this->assertCount(1, $response->getBins());
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $response->getBins()[0]);
    }

    public function testCorrectPackIntoManyRequest()
    {
        // Build a fake request
        // Build packing request
        $request = new \BinPacking3d\Entity\Request();

        $bin = new \BinPacking3d\Entity\Bin();
        $bin->setWidth(100);
        $bin->setHeight(120);
        $bin->setDepth(130);
        $bin->setMaxWeight(10);
        $bin->setOuterWidth(110);
        $bin->setOuterHeight(130);
        $bin->setOuterDepth(140);
        $bin->setWeight(0.1);
        $bin->setIdentifier('Test');
        $bin->setInternalIdentifier(1);
        $request->addBin($bin);

        // Item
        $item = new \BinPacking3d\Entity\Item();
        $item->setWidth(50);
        $item->setHeight(60);
        $item->setDepth(70);
        $item->setWeight(5);
        $item->setItemIdentifier('Test');
        $item->setProduct(['product_id' => 1]);
        $request->addItem($item);

        // Set extra info
        $request->setApiKey('API KEY');
        $request->setUsername('USERNAME');

        $packIntoMany = new PackIntoMany($request);
        $this->assertInstanceOf('\BinPacking3d\PackIntoMany', $packIntoMany);
        $this->assertInstanceOf('\BinPacking3d\Query', $packIntoMany);

        // Build response mock stack
        $mock = new MockHandler(
            [
                new Response(
                    200, ['Content-Type' => 'application/json'],
                    file_get_contents($this->getFilePath('responses/PackIntoMany/correct.json'))
                ),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // Get response
        $response = $client->get('/PackIntoMany');

        // Tests
        $this->assertEquals(200, $response->getStatusCode());

        // Get parsed JSON request
        $this->assertJsonStringEqualsJsonFile(
            $this->getFilePath('requests/PackIntoMany/request.json'),
            $packIntoMany->renderRequestJson()
        );

        // Get response and test it
        $response = $packIntoMany->handleResponse($response);
        $this->assertInstanceOf('\BinPacking3d\Entity\Packed', $response);
        $this->assertEquals(1, $response->count());
        $this->assertCount(1, $response->getBins());
        $this->assertInstanceOf('\BinPacking3d\Entity\Bin', $response->getBins()[0]);

        $count = 0;
        foreach ($response->yieldBins() as $bin) {
            $count++;
        }
        $this->assertEquals(1, $count);
    }

    public function testIncorrectPackIntoManyRequest()
    {
        // Build a fake request
        // Build packing request
        $request = new \BinPacking3d\Entity\Request();

        $packIntoMany = new PackIntoMany($request);
        $this->assertInstanceOf('\BinPacking3d\PackIntoMany', $packIntoMany);
        $this->assertInstanceOf('\BinPacking3d\Query', $packIntoMany);

        // Set expected exception
        $this->setExpectedException('\BinPacking3d\Exception\CriticalException');

        // Build response mock stack
        $mock = new MockHandler(
            [
                new Response(
                    200, ['Content-Type' => 'application/json'],
                    file_get_contents($this->getFilePath('responses/PackIntoMany/error_critical.json'))
                ),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // Get response
        $response = $client->get('/PackIntoMany');
        $packIntoMany->handleResponse($response);
    }

    public function testWarningPackIntoManyRequest()
    {
        // Build a fake request
        // Build packing request
        $request = new \BinPacking3d\Entity\Request();

        $packIntoMany = new PackIntoMany($request);
        $this->assertInstanceOf('\BinPacking3d\PackIntoMany', $packIntoMany);
        $this->assertInstanceOf('\BinPacking3d\Query', $packIntoMany);

        // Set expected exception
        $this->setExpectedException('\BinPacking3d\Exception\WarningException');

        // Build response mock stack
        $mock = new MockHandler(
            [
                new Response(
                    200, ['Content-Type' => 'application/json'],
                    file_get_contents($this->getFilePath('responses/PackIntoMany/error_warning.json'))
                ),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // Get response
        $response = $client->get('/PackIntoMany');
        $packIntoMany->handleResponse($response);
    }

    public function testErrorPackIntoManyRequest()
    {
        // Build a fake request
        // Build packing request
        $request = new \BinPacking3d\Entity\Request();

        $packIntoMany = new PackIntoMany($request);
        $this->assertInstanceOf('\BinPacking3d\PackIntoMany', $packIntoMany);
        $this->assertInstanceOf('\BinPacking3d\Query', $packIntoMany);

        // Set expected exception
        $this->setExpectedException('\Exception');

        // Build response mock stack
        $mock = new MockHandler(
            [
                new Response(
                    200, ['Content-Type' => 'application/json'],
                    file_get_contents($this->getFilePath('responses/PackIntoMany/error_other.json'))
                ),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        // Get response
        $response = $client->get('/PackIntoMany');
        $packIntoMany->handleResponse($response);
    }


    public function testQuery()
    {
        $request = new \BinPacking3d\Entity\Request();
        $query = new PackIntoMany($request);
    }

}
