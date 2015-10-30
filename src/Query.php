<?php namespace BinPacking3d;

use BinPacking3d\Entity\Packed;
use BinPacking3d\Entity\Request;
use BinPacking3d\Entity\Response;
use BinPacking3d\Exception\CriticalException;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class Query
 * @package BinPacking3d
 */
abstract class Query
{

    /**
     * Regions
     */
    const REGION_US = 'US'; // US AWS
    const REGION_EU = 'EU'; // EU AWS
    const REGION_GLOBAL = 'GLOBAL'; // Global API used Latency based routing to find US or EU API

    /**
     * @var
     */
    protected $endpoint;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var
     */
    private $params;

    /**
     * @var
     */
    private $cache;

    /**
     * @var string
     */
    private $region;

    /**
     * @var int
     */
    private $cacheTtl = 3600;

    /**
     * @param string $region
     */
    public function __construct($region = self::REGION_GLOBAL)
    {
        $this->region = $region;

        $this->client = ClientFactory::getInstance(
            $this->getUrl($region),
            $this->getPem($region)
        );

        $this->setLogger(new NullLogger);
    }

    /**
     * @param $region
     * @return string
     */
    public function getUrl($region)
    {
        switch ($region) {
            case self::REGION_US:
                return 'https://us-east.api.3dbinpacking.com/packer/';
            case self::REGION_EU:
                return 'https://eu.api.3dbinpacking.com/packer/';
            case self::REGION_GLOBAL:
            default:
                return 'https://global-api.3dbinpacking.com/packer/';
        }
    }

    /**
     * @param $region
     * @return string
     */
    public function getPem($region)
    {
        switch ($region) {
            case self::REGION_US:
                return __DIR__ . '/../cert/us-east.api.3dbinpacking.com.pem';
            case self::REGION_EU:
                return __DIR__ . '/../cert/eu.api.3dbinpacking.com.pem';
            case self::REGION_GLOBAL:
            default:
                return __DIR__ . '/../cert/global-api.3dbinpacking.com.pem';
        }
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return Query
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return Query
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return int
     */
    public function getCacheTtl()
    {
        return $this->cacheTtl;
    }

    /**
     * @param int $cacheTtl
     * @return Query
     */
    public function setCacheTtl($cacheTtl)
    {
        $this->cacheTtl = $cacheTtl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param mixed $logger
     * @return Query
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param Cache $cache
     * @return $this
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function run()
    {
        // Get url
        $url = $this->getEndpoint();

        // Run request
        try {
            // Build request
            $request = $this->renderRequest();
            $requestJson = $this->renderRequestJson();

            // Log request
            $this->log('info', ($this->cache ? 'Request to cache' : 'Request to 3dbinpacking'));
            $this->log('debug', $requestJson);

            // Build cache key
            $cacheKey = md5($requestJson);

            // If we have cache, check if we can get some result
            $contents = $this->getFromCache($cacheKey);
            if ($contents) {
                $this->log('info', 'Response from cache');
                $this->log('debug', $contents);

                return new Packed(new Response($contents), $this->request);
            }

            // No cache, or not connected, then we perform the real request
            $response = $this->client->get(
                $url, [
                    'json' => array_merge($request, ['params' => $this->getParams()]),
                ]
            );

            return $this->handleResponse($response, $cacheKey);
        } catch (RequestException $e) {
            throw new CriticalException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param mixed $endpoint
     * @return Query
     */
    protected function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @return array
     */
    public function renderRequest()
    {
        return $this->request->render();
    }

    /**
     * @return string
     */
    public function renderRequestJson()
    {
        return json_encode($this->renderRequest());
    }

    /**
     * @param $level
     * @param $message
     * @return bool
     */
    private function log($level, $message)
    {
        return $this->logger->{$level}($message);
    }

    /**
     * @param $cacheKey
     * @return mixed
     */
    private function getFromCache($cacheKey)
    {
        if ($this->cache && $this->cache->contains($cacheKey)) {
            return json_decode($this->cache->fetch($cacheKey));
        }
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     * @return Query
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Handle response and return
     *
     * @param \GuzzleHttp\Psr7\Response $response
     * @param null $cacheKey
     * @return Response
     * @throws \Exception
     */
    public function handleResponse(\GuzzleHttp\Psr7\Response $response, $cacheKey = null)
    {
        $contents = $response->getBody()->getContents();

        // Log response
        $this->log('info', 'Response from 3dbinpacking');
        $this->log('debug', $contents);

        if ($response->getStatusCode() === 200) {
            // If cache and we get here, save it
            $this->saveToCache($cacheKey, $contents);

            // Return Packed object with data processed
            return new Packed(new Response(json_decode($contents)), $this->request);
        }

        throw new CriticalException('Non 200 response');
    }

    /**
     * @param $cacheKey
     * @param $contents
     */
    private function saveToCache($cacheKey, $contents)
    {
        if ($this->cache && !is_null($cacheKey)) {
            return $this->cache->save($cacheKey, $contents, $this->cacheTtl);
        }

        return false;
    }

    /**
     * @param Request $request
     * @return $this
     */
    protected function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

}
