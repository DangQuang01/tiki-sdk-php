<?php

namespace Dangquang\TikiPhp;

abstract class Resource
{
    /** @var Client */
    protected $client;

    public function useApiClient(Client $client)
    {
        $this->client = $client;
    }

    public function call($endpoint, $data = [], $method = 'GET', $headers = [])
    {
        return $this->client->callApi($endpoint, $data, $method, $headers);
    }
}
