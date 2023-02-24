<?php


namespace Freeflygit\ShopeePhpSdk\request\shop;


use Freeflygit\ShopeePhpSdk\client\ShopeeApiConfig;
use Freeflygit\ShopeePhpSdk\node\shop\ShopWithoutBodyRequest;
use Freeflygit\ShopeePhpSdk\node\shop\ShopWithBodyRequest;

class ShopApiClient
{
    // GET Request
    public function httpCallGet($apiPath, $params, ShopeeApiConfig $apiConfig)
    {
        return ShopWithoutBodyRequest::makeGetMethod('GET',$apiPath, $params, $apiConfig);
    }

    // POST Request
    public function httpCallPost($apiPath, $params, $body, ShopeeApiConfig $apiConfig)
    {
        return ShopWithBodyRequest::postMethod($apiPath, $params, $body, $apiConfig);
    }

    // PUT Request
    public function httpCallPut($apiPath, $params, $body, ShopeeApiConfig $apiConfig)
    {
        return ShopWithBodyRequest::makeMethod('PUT', $apiPath, $params, $body, $apiConfig);
    }


    // PATCH Request
    public function httpCallPatch($apiPath, $params, $body, ShopeeApiConfig $apiConfig)
    {
        return ShopWithBodyRequest::makeMethod('PATCH', $apiPath, $params, $body, $apiConfig);
    }


    // DELETE Request
    public function httpCallDelete($apiPath, $params, $body, ShopeeApiConfig $apiConfig)
    {
        return ShopWithBodyRequest::makeMethod('DELETE', $apiPath, $params, $body, $apiConfig);
    }

}
