<?php

namespace Dangquang\TikiPhp\Resources;

use Dangquang\TikiPhp\Resource;

class Shop extends Resource
{
    // Lấy thông tin seller
    public function getSellerInfo()
    {
        $endpoint = 'integration/v2/sellers/me';
        $response = $this->call($endpoint);

        return $response; 
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

        $response = $this->call($endpoint, $params);

        return $response; 
    }

    // Cập nhật cài đặt "can_update_product"
    public function updateCanUpdateProduct($canUpdate)
    {
        $endpoint = 'integration/v1/sellers/me/updateCanUpdateProduct';
        $data = [
            'can_update_product' => $canUpdate,
        ];

        $response = $this->call($endpoint, $data, 'POST');

        return $response; 
    }
}