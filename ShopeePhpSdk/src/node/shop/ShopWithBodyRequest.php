<?php

/**
 * Open API Request for POST/PUT/DELETE/PATCH
 * @author Ravi Mukti
 * @since 26-08-2021
 */

namespace Freeflygit\ShopeePhpSdk\node\shop;


use GuzzleHttp\Client;
use Freeflygit\ShopeePhpSdk\client\ShopeeApiConfig;
use Exception;
use GuzzleHttp\Exception\ClientException;
use Freeflygit\ShopeePhpSdk\client\SignGenerator;

class ShopWithBodyRequest
{
    /**
     * Static Function For PUT/DELETE/PATCH request
     * @param $httpMethod
     * @param $apiPath
     * @param $params
     * @param $body
     * @param ShopeeApiConfig $apiConfig
     */
    public static function makeMethod($httpMethod, $apiPath, $params, $body, ShopeeApiConfig $apiConfig){

        // Set Header
        $header = array(
            "Content-type : application/json"
        );

        $requestUrl = (new SignGenerator($apiPath, $params,$apiConfig))->generateRequestUrl();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $requestUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $httpMethod,
            CURLOPT_HTTPHEADER => $header
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $data = json_decode(utf8_encode($response));

        if ($err) {
            return $err;
        } else {
            return $data;
        }
    }

    /**
     * @param $baseUrl
     * @param $apiPath
     * @param $params
     * @param $body
     * @param ShopeeApiConfig $apiConfig
     * @return object|array|mixed
     */
    public static function postMethod($apiPath, $params, $body, ShopeeApiConfig $apiConfig)
    {

        $baseUrl = $apiConfig->getBaseUrl();
        //Timestamp

        $requestUrl = (new SignGenerator($apiPath, $params,$apiConfig))->generateRequestUrl();

        $guzzleClient = new Client([
            'base_uri' => $baseUrl,
            'timeout' => 30.0
        ]);

        $response = null;
        $option = ['verify' => false];
        try
        {

            if (true === in_array($apiPath, ['/api/v2/media_space/upload_image', '/api/v2/media_space/upload_image'])) {
                foreach ($body as $key => $content) {
                    $option['multipart'][] = [
                        'name'      => $key,
                        'contents'  => $content
                    ];
                }
            } else {
                $option['json'] = $body;
            }

            $response = json_decode($guzzleClient->request('POST', $requestUrl, $option)->getBody()->getContents());

        } catch (ClientException $e)
        {
            $response = json_decode($e->getResponse()->getBody()->getContents());
        } catch(Exception $e)
        {
            $response = (object) array("error" => "GUZZLE_ERROR", "message" => $e->getMessage());
        }

        return $response;
    }

} // End of Class
