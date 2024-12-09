<?php

namespace Dangquang\TikiPhp\Errors;

class Exception extends \Exception
{
    protected $response;

    public function __construct($message = "", $code = "", $response = "")
    {
        parent::__construct($message);

        $this->code = $code;
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Hàm xử lý lỗi chung cho API
     * 
     * @param \Exception $e
     * @return array
     */
    public static function handleApiError(\Exception $e)
    {
        // Kiểm tra nếu là lỗi RequestException từ Guzzle
        if ($e instanceof \GuzzleHttp\Exception\RequestException) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'status_code' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : null,
                'response' => $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null,
            ];
        }

        // Xử lý các lỗi khác
        return [
            'error' => true,
            'message' => $e->getMessage(),
            'status_code' => null,
            'response' => null,
        ];
    }
}