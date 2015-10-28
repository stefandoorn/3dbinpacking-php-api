# 3dbinpacking.com PHP API Wrapper

[![Build Status](https://api.travis-ci.org/stefandoorn/3dbinpacking-php-api.svg?branch=master)](https://travis-ci.org/stefandoorn/3dbinpacking-php-api)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/stefandoorn/3dbinpacking-php-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/stefandoorn/3dbinpacking-php-api/?branch=master)
[![Test Coverage](https://codeclimate.com/github/stefandoorn/3dbinpacking-php-api/badges/coverage.svg)](https://codeclimate.com/github/stefandoorn/3dbinpacking-php-api/coverage)
[![Code Climate](https://codeclimate.com/github/stefandoorn/3dbinpacking-php-api/badges/gpa.svg)](https://codeclimate.com/github/stefandoorn/3dbinpacking-php-api)
[![StyleCI](https://styleci.io/repos/45122563/shield?style=flat)](https://styleci.io/repos/45122563)
[![Latest Stable Version](http://img.shields.io/packagist/v/stefandoorn/3dbinpacking-php-api.svg?style=flat)](https://packagist.org/packages/stefandoorn/3dbinpacking-php-api)
[![Total Downloads](https://img.shields.io/packagist/dt/stefandoorn/3dbinpacking-php-api.svg?style=flat)](https://packagist.org/packages/stefandoorn/3dbinpacking-php-api)
[![License](https://img.shields.io/packagist/l/stefandoorn/3dbinpacking-php-api.svg?style=flat)](https://packagist.org/packages/stefandoorn/3dbinpacking-php-api)

This library acts as a PHP wrapper around the API available at [3dbinpacking.com](http://www.3dbinpacking.com).

## Table Of Content

1. [Requirements](#requirements)
2. [Installation](#installation)
3. [License](#license-section)

<a name="requirements"></a>
## Requirements

This library uses PHP 5.5+.

To use the 3dbinpacking.com API Wrapper, you have to [request an access key from 3dbinpacking.com](http://www.3dbinpacking.com). For every request,
you will have to provide the username & API Key.

Note: this service is **NOT** free of usage.

<a name="installation"></a>
## Installation

It is recommended that you install the library [through composer](http://getcomposer.org/). To do so,
run the Composer command to install the latest stable version of the API wrapper:

```shell
composer require stefandoorn/3dbinpacking-php-api
```

## Example

````
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

// Perform request and get results
$boxes = $packIntoMany->run();

// Process result, in here we get all the packed boxes including the items per box
foreach ($boxes->yieldBins() as $packedBox) {
    // Get weight of box
    $weight = $packedBox->getUsedWeight();

    // Get dimensions
    $height = $packedBox->getOuterHeight();
    $width = $packedBox->getOuterWidth();
    $depth = $packedBox->getOuterDepth();

    // Get identifier
    $identifier = $packedBin->getIdentifier();

    // Get items in this box
    foreach ($packedBox->yieldItems() as $item) {
    	// Get additional product data supplied (e.g. IDs, SKUs, etc)
    	$product = $item->getProduct();

    	// Add to database etc...
    }
}
````

Optional you can add a [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) compatible logger to the Request object:

````
$log = new \Monolog\Logger('binpacking');
$log->pushHandler(new \Monolog\Handler\StreamHandler('binpacking.log', \Monolog\Logger::DEBUG));
````

Optional you can add a Cache driver compatible with [doctrine/cache](https://github.com/doctrine/cache), e.g.:

````
$cacheDriver = new \Doctrine\Common\Cache\RedisCache();
$redis = new Redis;
$redis->connect($redisHost);
$cacheDriver->setRedis($redis);
$packIntoMany->setCache($cacheDriver);
````

<a name="license-section"></a>
## License

3dbinpacking.com API Wrapper is licensed under [The MIT License (MIT)](LICENSE).
