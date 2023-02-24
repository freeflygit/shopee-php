# Shopee PHP Client

This is a Shopee PHP Client, currently supported for **API V2** in [ShopeeOpenPlatform](https://open.shopee.com/documents?module=87&type=2&id=64&version=2)

## Composer Install
`composer require flyer/shopee-php-sdk`

## .env

```
SHOPEE_PARTNER_KEY=
SHOPEE_PARTNER_ID=
SHOPEE_BASE_URL=https://partner.shopeemobile.com

```
## Usage
```
$shopeeClient = new ShopApiClient();
$apiConfig = new ShopeeApiConfig();

#1.配置店铺:
$apiConfig->setShopId($shop_id)->setAccessToken($shop_access_token);

#2.配置商户:
$apiConfig->setMerchantId($merchant_id)->setAccessToken($merchant_access_token);

GET 请求	:	
$apiPath = "/api/v2/product/get_item_list";
$params = array();
$productList = $shopeeClient->httpCallGet($baseUrl, $apiPath, $params, $apiConfig);

POST请求:

$apiPath = "/api/v2/media_space/upload_image";
        $params = [];
        $body = [
           'image'=>fopen('https://file.nantang-tech.com/uploads/20210820/ded1e4ccb41ca87aa89bf903582335d9.jpg?x-oss-process=image/auto-orient,1/resize,m_fixed,w_850,h_850/watermark,bucket_nt-product,image_d2F0ZXJtYXJrLzZhOTU3NGMzYTJmYzBlZjY5NjkwMmJhZGZhMDE5N2JmLnBuZz94LW9zcy1wcm9jZXNzPWltYWdlL2F1dG8tb3JpZW50LDEvcmVzaXplLG1fZml4ZWQsd18zMDAsaF8zMDA=,g_nw,x_0,y_0','r'),
        ];

        $res = $shopeeClient->httpCallPost($apiPath, $params,$body, $apiConfig);

```

## Running Test

`composer test`

For running all tests
