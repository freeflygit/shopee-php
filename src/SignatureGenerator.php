<?php

namespace Shopee;

use Psr\Http\Message\UriInterface;
use function hash_hmac;

class SignatureGenerator
{
    private $client;
    private $timestamp;

    public function __construct(\Shopee\Client $client)
    {
        $this->client = $client;
        $this->timestamp = time();
    }

    public function generateSignature(UriInterface $uri, array $params): string
    {
        #1。拼接基础字符串
        if($this->client->getMerchantId()){
            $baseString = $this->client->getPartnerId()."".$uri->getPath()."".$this->timestamp."".$this->client->getAccessToken()."".$this->client->getMerchantId();
        }elseif($this->client->getShopId()){
            $baseString = $this->client->getPartnerId()."".$uri->getPath()."".$this->timestamp."".$this->client->getAccessToken()."".$this->client->getShopId();
        }else{
            $baseString = $this->client->getPartnerId()."".$uri->getPath().$this->timestamp;
        }
        return  hash_hmac('sha256', utf8_encode($baseString), $this->client->getSecrect());
    }

    /**
     * @param string $sign
     * @param array $params
     * @return string
     * @desc 获取查询字符串
     */
    public  function generateQueryStr(string $sign,array $params) :string
    {
        $queryStr = '';
        if ($params != null){
            foreach ($params as $key => $value){
                if(is_array($value)){
                    foreach ($value as $vv){
                        $queryStr .= "&". $key . "=" . urlencode($vv);
                    }
                }else{
                    $queryStr .= "&". $key . "=" . urlencode($value);
                }

            }
        }

        if($this->client->getShopId()) {
            $queryStr .=  "&" . "partner_id=" . urlencode($this->client->getPartnerId()) . "&" . "shop_id=" . urlencode($this->client->getShopId()) . "&" . "access_token=" . urlencode($this->client->getAccessToken()) . "&" . "timestamp=" . urlencode($this->timestamp) . "&" . "sign=" . urlencode($sign);
        }elseif($this->client->getMerchantId()){
            $queryStr .=  "&" . "partner_id=" . urlencode($this->client->getPartnerId()) . "&" . "merchant_id=" . urlencode($this->client->getMerchantId()) . "&" . "access_token=" . urlencode($this->client->getAccessToken()) . "&" . "timestamp=" . urlencode($this->timestamp) . "&" . "sign=" . urlencode($sign);
        }else{
            $queryStr .=  "&" . "partner_id=" . urlencode($this->client->getPartnerId()) .  "&" . "timestamp=" . urlencode($this->timestamp) . "&" . "sign=" . urlencode($sign);
        }

        return ltrim($queryStr,'&');
    }





}
