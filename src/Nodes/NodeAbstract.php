<?php

namespace Shopee\Nodes;

use Psr\Http\Message\UriInterface;
use Shopee\Client;
use Shopee\RequestParameters;
use Shopee\RequestParametersInterface;
use Shopee\ResponseData;

abstract class NodeAbstract
{
    /** @var Client */
    protected $client;
    protected $version = 'v2';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string|UriInterface $uri
     * @param array|RequestParameters $parameters
     */
    public function post($uri, $parameters)
    {
        if ($parameters instanceof RequestParametersInterface) {
            $parameters = $parameters->toArray();
        }
        return $this->client->newRequest($uri, [], $parameters,'post');


    }


    /**
     * @param string|UriInterface $uri
     * @param array|RequestParameters $parameters

     */
    public function get($uri, $parameters)
    {
        if ($parameters instanceof RequestParametersInterface) {
            $parameters = $parameters->toArray();
        }
        return  $this->client->newRequest($uri, [], $parameters);
    }
}
