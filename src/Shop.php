<?php

namespace Dangquang\TikiPhp;

use Dangquang\TikiPhp\Client;

class Shop
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    // Lấy thông tin seller
    public function getSellerInfo()
    {
        $endpoint = 'integration/v2/sellers/me';
        $response = $this->client->callApi($endpoint);

        if (isset($response['error']) && $response['error']) {
            return $response; // Trả về thông tin lỗi nếu có
        }

        return $response; // Trả về thông tin seller
    }

    // Lấy danh sách kho hàng của seller
    public function getSellerWarehouses($status = 1, $type = 1, $limit = 20, $page = 1)
    {
        $endpoint = 'integration/v2/sellers/me/warehouses';
        $params = [
            'status' => $status,
            'type' => $type,
            'limit' => $limit,
            'page' => $page,
        ];

        $response = $this->client->callApi($endpoint, $params);

        if (isset($response['error']) && $response['error']) {
            return $response; // Trả về thông tin lỗi nếu có
        }

        return $response; // Trả về danh sách kho hàng
    }

    // Cập nhật cài đặt "can_update_product"
    public function updateCanUpdateProduct($canUpdate)
    {
        $endpoint = 'integration/v1/sellers/me/updateCanUpdateProduct';
        $data = [
            'can_update_product' => $canUpdate,
        ];

        $response = $this->client->callApi($endpoint, $data, 'POST');

        if (isset($response['error']) && $response['error']) {
            return $response; // Trả về thông tin lỗi nếu có
        }

        return $response; // Trả về kết quả thành công
    }
}