<?php
namespace Dangquang\TikiPhp\Resources;

use Dangquang\TikiPhp\Client;
use Dangquang\TikiPhp\Resource;
class Order extends Resource
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Lấy danh sách đơn hàng
     *
     * @param array $params Tham số truy vấn (page, limit, status,...)
     * @return array
     */
    public function getOrderList(array $params = [])
    {
        return $this->client->callApi('v2/orders', $params);
    }

    /**
     * Lấy chi tiết một đơn hàng
     *
     * @param string $orderId ID của đơn hàng
     * @return array
     */
    public function getOrderDetail($orderId)
    {
        return $this->client->callApi("v2/orders/{$orderId}");
    }

    /**
     * Xác nhận đủ hàng cho một đơn hàng (Drop shipping)
     *
     * @param string $orderId ID của đơn hàng
     * @param array $data Dữ liệu xác nhận (confirmation_status, seller_inventory_id)
     * @return array
     */
    public function confirmAvailableStock($orderId, array $data)
    {
        return $this->client->callApi("v2/orders/{$orderId}/dropship/confirm-available", $data, 'POST');
    }

    /**
     * Lấy thời gian pickup dự kiến cho đơn hàng Drop Shipping
     *
     * @return array
     */
    public function getExpectedPickupTimes()
    {
        return $this->client->callApi('v2/orders/dropship/expected-pickup-slots');
    }

    /**
     * Xác nhận hoàn thành đóng gói cho Drop Shipping
     *
     * @param string $orderId ID của đơn hàng
     * @param int $sellerInventoryId ID kho của người bán
     * @return array
     */
    public function confirmPacking($orderId, $sellerInventoryId)
    {
        $data = [
            'seller_inventory_id' => $sellerInventoryId,
        ];

        return $this->client->callApi("v2/orders/{$orderId}/dropship/confirm-packing", $data, 'POST');
    }

    /**
     * Xác nhận đủ hàng cho đơn hàng Fulfillment On-demand
     *
     * @param string $orderId ID của đơn hàng
     * @param array $data Dữ liệu xác nhận (confirmation_status, seller_inventory_id)
     * @return array
     */
    public function confirmOdfStock($orderId, array $data)
    {
        return $this->client->callApi("v2/orders/{$orderId}/confirm-available", $data, 'POST');
    }

    /**
     * Cập nhật trạng thái giao hàng cho Seller Delivery
     *
     * @param string $orderId ID của đơn hàng
     * @param string $status Trạng thái giao hàng
     * @return array
     */
    public function updateShipmentStatus($orderId, $status)
    {
        $data = [
            'status' => $status,
        ];

        return $this->client->callApi("v2/orders/{$orderId}/update-shipment-status", $data, 'POST');
    }
}
