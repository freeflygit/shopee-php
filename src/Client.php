<?php

namespace Shopee;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Utils;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Shopee\Nodes\NodeAbstract;
use Shopee\Nodes;
use Shopee\Exception\Api\AuthException;
use Shopee\Exception\Api\BadRequestException;
use Shopee\Exception\Api\ClientException;
use Shopee\Exception\Api\Factory;
use Shopee\Exception\Api\ServerException;

use function array_key_exists;
use function array_merge;
use function getenv;
use function json_encode;
use function time;
use function substr;

/**
 * @property Nodes\Item\Item $item
 * @property Nodes\Logistics\Logistics $logistics
 * @property Nodes\Order\Order $order
 * @property Nodes\Returns\Returns $returns
 * @property Nodes\Shop\Shop $shop
 * @property Nodes\Discount\Discount $discount
 * @property Nodes\ShopCategory\ShopCategory $shopCategory
 * @property Nodes\Image\Image $image
 * @property Nodes\Push\Push $push
 * @property Nodes\Payment\Payment $payment
 */
class Client
{
    public const VERSION = '0.2';

    public const DEFAULT_BASE_URL = 'https://partner.shopeemobile.com';

    public const DEFAULT_USER_AGENT = 'shopee-php/' . self::VERSION;

    public const ENV_SECRET_NAME = 'SHOPEE_API_SECRET';

    public const ENV_PARTNER_ID_NAME = 'SHOPEE_PARTNER_ID';

    public const ENV_SHOP_ID_NAME = 'SHOPEE_SHOP_ID';

    public const ENV_MERCHANT_ID_NAME = 'SHOPEE_MERCHANT_ID';

    public const ENV_BASE_URL_NAME = 'SHOPEE_BASE_URL';

    public const ENV_USER_AGENT_NAME = 'SHOPEE_USER_AGENT';

    public const ENV_ACCESS_TOKEN_NAME = 'SHOPEE_ACCESS_TOKEN';


    /** @var ClientInterface */
    protected $httpClient;

    /** @var UriInterface */
    protected $baseUrl;

    /** @var string */
    protected $userAgent;

    /** @var string Shopee Partner Secret key */
    protected $secret;

    /** @var int */
    protected $partnerId;

    /** @var int */
    protected $shopId;

    /** @var NodeAbstract[] */
    public $nodes = [];

    /** @var SignatureGeneratorInterface */
    protected $signatureGenerator;

    /** @var int */
    protected $merchantId;

    /** @var string Shopee access_token */
    protected $accessToken;

    public function __construct(array $config = [])
    {
        $config = array_merge([
            'httpClient' => null,
            'baseUrl' => env(self::ENV_BASE_URL_NAME,self::DEFAULT_BASE_URL),
            'userAgent' => env(self::ENV_USER_AGENT_NAME,self::DEFAULT_USER_AGENT),
            'secret' => getenv(self::ENV_SECRET_NAME),
            'partner_id' => (int)env(self::ENV_PARTNER_ID_NAME,0),
            'shopid' => (int)env(self::ENV_SHOP_ID_NAME,0),
            'merchant_id' => (int)env(self::ENV_MERCHANT_ID_NAME,0),
            'access_token' => env(self::ENV_ACCESS_TOKEN_NAME,0),
            SignatureGeneratorInterface::class => null,
        ], $config);

        $this->httpClient = $config['httpClient'] ?: new HttpClient();
        $this->setBaseUrl($config['baseUrl']);
        $this->setUserAgent($config['userAgent']);
        $this->secret = $config['secret'];
        $this->partnerId = $config['partner_id'];
        $this->shopId = $config['shopid'];
        $this->merchantId = $config['merchant_id'];
        $this->accessToken = $config['access_token'];

        $signatureGenerator = $config[SignatureGeneratorInterface::class];
        if (is_null($signatureGenerator)) {
            $this->signatureGenerator = new SignatureGenerator($this);
        } elseif ($signatureGenerator instanceof SignatureGeneratorInterface) {
            $this->signatureGenerator = $signatureGenerator;
        } else {
            throw new InvalidArgumentException('Signature generator not implement SignatureGeneratorInterface');
        }

        (new NodeGenerator())->setNodeList($this);

    }

    public function __get(string $name)
    {
        if (!array_key_exists($name, $this->nodes)) {
            throw new InvalidArgumentException(sprintf('Property "%s" not exists', $name));
        }

        return $this->nodes[$name];
    }

    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @param ClientInterface $client
     * @return $this
     */
    public function setHttpClient(ClientInterface $client)
    {
        $this->httpClient = $client;

        return $this;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     * @return $this
     */
    public function setUserAgent(string $userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getBaseUrl(): UriInterface
    {
        return $this->baseUrl;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setBaseUrl(string $url)
    {
        $this->baseUrl = new Uri($url);

        return $this;
    }

    public function getDefaultParameters(): array
    {
        return [
            'partner_id' => $this->partnerId,
            'secrect' => $this->secret,
            'timestamp' => time(), // Put the current UNIX timestamp when making a request
        ];
    }

    public function getShopId(): int
    {
        return $this->shopId;
    }

    /**
     * @param int $shopId
     * @return $this
     */
    public function setShopId(int $shopId)
    {
        $this->shopId = $shopId;

        return $this;
    }

    public function getMerchantId(): int
    {
        return $this->merchantId;
    }

    /**
     * @param int $merchantId
     * @return $this
     */
    public function setMerchantId(int $merchantId)
    {
        $this->merchantId = $merchantId;

        return $this;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     * @return $this
     */
    public function setAccessToken(string $accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
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
     * @return string
     */
    public function getSecrect()
    {
        return $this->secret;
    }

    /**
     * @param string $secretKey
     */
    public function setSecrect($secretKey)
    {
        $this->secret = $secretKey;
        return $this;
    }

    /**
     * Create HTTP JSON body
     *
     * The HTTP body should contain a serialized JSON string only
     *
     * @param array $data
     * @return string
     */
    protected function createJsonBody(array $data): array
    {
        return json_encode(array_merge($this->getDefaultParameters(), $data));
    }

    /**
     * Generate an HMAC-SHA256 signature for a HTTP request
     *
     * @param UriInterface $uri
     * @param string $body
     * @return string
     */
    protected function signature(UriInterface $uri, array $params): string
    {
        return $this->signatureGenerator->generateSignature($uri, $params);
    }

    /**
     * @param string|UriInterface $uri
     * @param array $headers
     * @param array $data
     */
    public function newRequest($uri, array $headers = [], $data = [],$method = 'GET')
    {
        $uri = Utils::uriFor($uri);
        $path = $this->baseUrl->getPath() . $uri->getPath();
        //dd($path);
        if (substr($path, 0, 1) !== '/') {
            $path = '/' . $path;
        }
        $uri = $uri->withPath($path);

        $sign  = $this->signature($uri, $data);

        $params = strtoupper($method) == 'GET' ? $data : [];

        $queryStr = $this->signatureGenerator->generateQueryStr($sign,$params);
        $url = sprintf('%s://%s%s?%s',$this->baseUrl->getScheme(),$this->baseUrl->getHost(),$path,$queryStr);

        $header = [
            'verify'=>false,
            'timeout'=>0
        ];
        $client = new \GuzzleHttp\Client(['headers'=>$header]);
        $option = [];
        if(strtoupper($method) == 'POST'){
            if (true === in_array($path, ['/api/v2/media_space/upload_image'])) {
                foreach ($data as $key => $content) {
                    $option['multipart'][] = [
                        'name'      => $key,
                        'contents'  => $content
                    ];
                }
            } else {
                $option['json'] = $data;
            }
        }
        
        try {
            $response = $client->request($method, $url, $option);
        } catch (GuzzleClientException $exception) {
            switch ($exception->getCode()) {
                case 400:
                    $className = BadRequestException::class;
                    break;
                case 403:
                    $className = AuthException::class;
                    break;
                default:
                    $className = ClientException::class;
            }

            throw Factory::create($className, $exception);
        } catch (GuzzleServerException $exception) {
            throw Factory::create(ServerException::class, $exception);
        }
        //return $response;

        return new ResponseData($response);

    }

    public function send(RequestInterface $request): ResponseInterface
    {
        try {
            $response = $this->httpClient->send($request);
        } catch (GuzzleClientException $exception) {
            switch ($exception->getCode()) {
                case 400:
                    $className = BadRequestException::class;
                    break;
                case 403:
                    $className = AuthException::class;
                    break;
                default:
                    $className = ClientException::class;
            }

            throw Factory::create($className, $exception);
        } catch (GuzzleServerException $exception) {
            throw Factory::create(ServerException::class, $exception);
        }

        return $response;
    }

}
