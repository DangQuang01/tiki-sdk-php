<?php

namespace Dangquang\TikiPhp;

use Dangquang\TikiPhp\Errors\Exception;
use GuzzleHttp\Client as GuzzleClient;
use Dangquang\TikiPhp\Resources\Shop;
use Dangquang\TikiPhp\Resources\Order;

class Client
{
    protected $resources = [
        Shop::class,
        Order::class,
    ];

    private $client;
    private $accessToken;

    private $apiKey;
    private $apiSecret;

    public function __construct($apiKey, $apiSecret)
    {
        $this->client = new GuzzleClient([
            'base_uri' => Config::get('base_url'),
        ]);

        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getApiSecret()
    {
        return $this->apiSecret;
    }


    public function auth()
    {
        return new Auth($this);
    }

    public function callApi($endpoint, $data = [], $method = 'GET', $headers = [])
    {
        $method = strtoupper($method);

        $options = [];

        if ($headers) {
            $options['headers'] = $headers;
        } else {
            $options['headers'] = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->accessToken,
            ];
        }

        if ($method === 'POST') {
            $options['form_params'] = $data;
        } else {
            $options['query'] = $data;
        }

        try {

            $response = $this->client->request($method, $endpoint, $options);

            return json_decode($response->getBody()->getContents(), true);

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return Exception::handleApiError($e);
        } catch (\Exception $e) {
            return Exception::handleApiError($e);
        }
    }


    public function __get($resourceName)
    {
        $resourceClassName = __NAMESPACE__ . "\\Resources\\" . ucfirst($resourceName);

        if (!in_array($resourceClassName, $this->resources)) {
            $resourceClassName = null;
            foreach ($this->resources as $resource) {

                if (strpos($resource, __NAMESPACE__ . "\\Resources\\") === 0) {
                    continue;
                }


                $lookup = "\\" . $resourceName;
                if (0 === substr_compare($resource, $lookup, -strlen($lookup))) {
                    $resourceClassName = $resource;
                    break;
                }
            }
        }

        if ($resourceClassName === null) {
            throw new Exception("Invalid resource " . $resourceName);
        }


        $resource = new $resourceClassName();
        if (!$resource instanceof Resource) {
            throw new Exception("Invalid resource object " . $resourceName);
        }

        $resource->useApiClient($this);

        return $resource;
    }



}