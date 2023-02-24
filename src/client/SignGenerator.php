<?php


namespace Haistar\ShopeePhpSdk\client;


class SignGenerator
{
    public  $timeStamp;
    public $apiPath;
    public $params;
    public $apiConfig;
    public $signedKey;


    public function __construct($apiPath,$params,ShopeeApiConfig $apiConfig)
    {
        $this->apiPath = $apiPath;
        $this->params = $params;
        $this->apiConfig = $apiConfig;
        $this->timeStamp = time();
    }

    /**
     * @param $baseString
     * @param $key
     * @return false|string
     * @desc 计算签名
     */
    protected function generateSign(){
        $baseString = $this->generateBaseString();
        $key = $this->apiConfig->getSecretKey();
         return  hash_hmac('sha256', utf8_encode($baseString), $key);
    }

    /**
     * @param $apiPath
     * @param ShopeeApiConfig $apiConfig
     * @return string
     * @desc 拼接基础字符
     */
    protected  function generateBaseString()
    {
        // Concatenate Base String
        if($this->apiConfig->getMerchantId()){
            $baseString = $this->apiConfig->getPartnerId()."".$this->apiPath."".$this->timeStamp."".$this->apiConfig->getAccessToken()."".$this->apiConfig->getMerchantId();
        }elseif($this->apiConfig->getShopId()){
            $baseString = $this->apiConfig->getPartnerId()."".$this->apiPath."".$this->timeStamp."".$this->apiConfig->getAccessToken()."".$this->apiConfig->getShopId();
        }else{
            $baseString = $this->apiConfig->getPartnerId()."".$this->apiPath.$this->timeStamp;
        }

        return $baseString;
    }

   public  function generateRequestUrl()
    {
        $signedKey = $this->generateSign();
        $this->apiPath .= "?";
        $baseUrl = $this->apiConfig->getBaseUrl();
        if ($this->params != null){
            foreach ($this->params as $key => $value){
                if(is_array($value)){
                    foreach ($value as $vv){
                        $this->apiPath .= "&". $key . "=" . urlencode($vv);
                    }
                }else{
                    $this->apiPath .= "&". $key . "=" . urlencode($value);
                }

            }
        }

        if($this->apiConfig->getShopId()) {
            $requestUrl = $baseUrl . $this->apiPath . "&" . "partner_id=" . urlencode($this->apiConfig->getPartnerId()) . "&" . "shop_id=" . urlencode($this->apiConfig->getShopId()) . "&" . "access_token=" . urlencode($this->apiConfig->getAccessToken()) . "&" . "timestamp=" . urlencode($this->timeStamp) . "&" . "sign=" . urlencode($signedKey);
        }elseif($this->apiConfig->getMerchantId()){
            $requestUrl = $baseUrl . $this->apiPath . "&" . "partner_id=" . urlencode($this->apiConfig->getPartnerId()) . "&" . "merchant_id=" . urlencode($this->apiConfig->getMerchantId()) . "&" . "access_token=" . urlencode($this->apiConfig->getAccessToken()) . "&" . "timestamp=" . urlencode($this->timeStamp) . "&" . "sign=" . urlencode($signedKey);
        }else{
            $requestUrl = $baseUrl . $this->apiPath . "&" . "partner_id=" . urlencode($this->apiConfig->getPartnerId()) .  "&" . "timestamp=" . urlencode($this->timeStamp) . "&" . "sign=" . urlencode($signedKey);
        }

        return $requestUrl;
    }

}
