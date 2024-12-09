<?php

namespace Dangquang\TikiPhp;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
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

    public function order()
    {
        return new Order($this);
    }

    public function shop(){
        return new Shop($this);
    }

    public function callApi($endpoint, $data = [], $method = 'GET', $headers = [])
    {
        $method = strtoupper($method);

        $options = [];

        echo "Token: ". $this->accessToken;

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
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'status_code' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : null,
                'response' => $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null,
            ];
        } catch (\Exception $e) {
            // Các lỗi khác
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'status_code' => null,
                'response' => null,
            ];
        }
    }



}