<?php

namespace Dangquang\TikiPhp;

class Auth
{
    protected $client;

    protected $clientId;

    protected $clientSecret;

    public function __construct(Client $client)
    {
        $this->client = $client;

        $this->clientId = $this->client->getApiKey();
        $this->clientSecret = $this->client->getApiSecret();
    }

    public function createAuthRequest($redirectUri, $return_url = false)
    {
        $state = $this->generateState();

        $query = [
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUri,
            'scope' => 'offline product order',
            'state' => $state,
        ];

        $_SESSION['tiki_oauth2_state'] = $state;

        $authorizationUrl = Config::get('base_url') . Config::get('auth_endpoint') . '?' . http_build_query($query);

        return $return_url ? $authorizationUrl : header('Location: ' . $authorizationUrl);

    }

    public function getToken($code, $redirectUri, $state)
    {
        $checkState = $this->validateState($state);

        // if (!$checkState) {
        //     return false;
        // }

        $response = $this->client->callApi(Config::get('token_endpoint'), [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUri,
            // 'client_secret' => $this->clientSecret,
        ], 'POST', $this->getHeaders());

        echo json_encode($response, JSON_PRETTY_PRINT);

        if (isset($response['error']) && $response['error'] == true) {
            return false;
        }

        $this->client->setAccessToken($response['access_token']);

        return $response;
    }

    public function refreshToken($refreshToken)
    {
        $response = $this->client->callApi(Config::get('token_endpoint'), [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $this->clientId,
            // 'client_secret' => $this->clientSecret,
        ], 'POST', $this->getHeaders());

        if (isset($response['error']) && $response['error'] == true) {
            return false;
        }

        if (isset($response['access_token'])) {
            $this->client->setAccessToken($response['access_token']);
            return $response;
        }

    }

    public function getClientCredentialsToken()
    {
        $response = $this->client->callApi(Config::get('token_endpoint'), [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            // 'client_secret' => $this->clientSecret,
        ], 'POST', $this->getHeaders());

        if (isset($response['error']) && $response['error'] == true) {
            return false;
        }

        if (isset($response['access_token'])) {
            $this->client->setAccessToken($response['access_token']);
            return $response;
        }
    }

    private function generateState($length = 32)
    {
        return bin2hex(random_bytes($length / 2));
    }

    protected function validateState($state)
    {
        if (!isset($_SESSION['tiki_oauth2_state']) || $_SESSION['tiki_oauth2_state'] !== $state) {
            return false;
        }
        unset($_SESSION['tiki_oauth2_state']);
        return true;
    }

    private function getHeaders()
    {
        $authHeader = base64_encode("{$this->clientId}:{$this->clientSecret}");

        return [
            'Authorization' => "Basic {$authHeader}",
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }
}
