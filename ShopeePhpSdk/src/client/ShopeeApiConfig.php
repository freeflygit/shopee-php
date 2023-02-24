<?php

/**
 * @see https://open.shopee.com/documents?module=87&type=2&id=58&version=2
 * Shopee Client Constructor
 * @author Ravi Mukti
 * @since 26-08-2021
 */

namespace Freeflygit\ShopeePhpSdk\client;


class ShopeeApiConfig
{
    private $partnerId;
    private $accessToken;
    private $shopId;
    private $secretKey;
    private $merchantId;
    private $baseUrl;

    /**
     * ShopeeApiConfig constructor.
     * @param string $shopId
     * @param string $merchantId
     * @param string $accessToken
     */
    public function __construct($shopId = "",$merchantId = '',$accessToken = "")
    {
        $this->partnerId = env('SHOPEE_PARTNER_ID');
        $this->secretKey = env('SHOPEE_PARTNER_KEY');
        $this->baseUrl = env('SHOPEE_BASE_URL');
        $this->accessToken = $accessToken;
        $this->merchantId = $merchantId;
        $this->shopId = $shopId;
        if ($this->partnerId == "") throw new Exception("Environment variables SHOPEE_PARTNER_ID are not configured");
        if ($this->secretKey == "") throw new Exception("Environment variables SHOPEE_PARTNER_KEY are not configured");
        if ($this->baseUrl == "") throw new Exception("Environment variables SHOPEE_BASE_URL are not configured");
    }

    /**
     * @return mixed
     */
    public function getPartnerId()
    {
        return $this->partnerId;
    }

    /**
     * @param mixed $partnerId
     */
    public function setPartnerId($partnerId)
    {
        $this->partnerId = $partnerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param mixed $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @param mixed|string $refreshToken
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * @param mixed $shopId
     */
    public function setShopId($shopId)
    {
        $this->shopId = $shopId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @param string $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    /**
     * @param $baseUrl
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
    
}
