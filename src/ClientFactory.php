<?php namespace BinPacking3d;

use GuzzleHttp\Client;

class ClientFactory
{

    public static function getInstance($url, $pem)
    {
        return new Client(
            [
                'base_uri' => $url,
                'timeout' => 2.0,
                'verify' => $pem,
            ]
        );
    }
}
