<?php


namespace Freeflygit\ShopeePhpSdk\node\shop;


use Freeflygit\ShopeePhpSdk\client\ShopeeApiConfig;
use Freeflygit\ShopeePhpSdk\client\SignGenerator;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ShopWithoutBodyRequest
{
    /**
     * @param $httpMethod
     * @param $baseUrl
     * @param $apiPath
     * @param $params
     * @param ShopeeApiConfig $apiConfig
     * @return mixed|string
     */
    public static function makeGetMethod($httpMethod, $apiPath, $params, ShopeeApiConfig $apiConfig){
        // Validate Input
        //Timestamp
        $baseUrl = $apiConfig->getBaseUrl();
        //$timeStamp = time();
        $requestUrl = (new SignGenerator($apiPath, $params,$apiConfig))->generateRequestUrl();
        //dd($requestUrl);

        $guzzleClient = new Client([
            'base_uri' => $baseUrl,
            'timeout' => 30.0
        ]);

        $response = null;

        try
        {
            $response = json_decode($guzzleClient->request($httpMethod, $requestUrl)->getBody()->getContents());
        } catch (ClientException $e)
        {
            $response = json_decode($e->getResponse()->getBody()->getContents());
        } catch(Exception $e)
        {
            $response = (object) array("error" => "GUZZLE_ERROR", "message" => $e->getMessage());
        }

        return $response;
    }

} // End Of Class
