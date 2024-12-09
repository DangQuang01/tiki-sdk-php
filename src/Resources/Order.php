<?php
namespace Dangquang\TikiPhp\Resources;

use Dangquang\TikiPhp\Resource;

class Order extends Resource
{
    /**
     * Lấy danh sách đơn hàng
     *
     * @param array $params Tham số truy vấn (page, limit, status,...)
     * @return array
     */
    public function getOrderList(array $params = [])
    {
        $url = "integration/v2/orders";

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $this->call($url);
    }

    /**
     * Lấy chi tiết một đơn hàng
     *
     * @param string $orderId ID của đơn hàng
     * @param string|null $include Các trường bổ sung cần lấy (ví dụ: status_histories,item.fees)
     * @return array
     */
    public function getOrderDetail($orderId, $include = null)
    {
        $url = "integration/v2/orders/{$orderId}";

        if ($include) {
            $url .= "?include={$include}";
        }

        return $this->call($url);
    }


    /**
     * Xác nhận đủ hàng tồn kho cho đơn hàng
     *
     * @param string $orderId ID của đơn hàng cần xác nhận
     * @param array $availableItemIds Danh sách item IDs có đủ hàng
     * @param int $sellerInventoryId ID của kho hàng của seller
     * @return array Kết quả xác nhận
     */
    public function confirmEnoughStock($orderId, array $availableItemIds, $sellerInventoryId)
    {
        $url = "integration/v2/orders/{$orderId}/confirm-available";

        $payload = [
            'available_item_ids' => $availableItemIds,
            'seller_inventory_id' => $sellerInventoryId,
        ];
        return $this->call($url, $payload, 'POST');
    }

    /**
     * Xác nhận đủ hàng tồn kho cho đơn hàng Drop Shipping
     *
     * @param string $orderId ID của đơn hàng cần xác nhận
     * @param string $confirmationStatus Trạng thái xác nhận (seller_confirmed hoặc seller_canceled)
     * @param int $sellerInventoryId ID của kho hàng của seller
     * @return array Kết quả xác nhận
     */
    public function confirmEnoughStockForDropShipping($orderId, $confirmationStatus, $sellerInventoryId)
    {
        $url = "integration/v2/orders/{$orderId}/dropship/confirm-available";

        $payload = [
            'confirmation_status' => $confirmationStatus,
            'seller_inventory_id' => $sellerInventoryId,
        ];

        return $this->call($url, $payload, 'POST');
    }

    /**
     * Lấy danh sách kho hàng của seller tương ứng với mã kho Tiki
     *
     * @param array $tikiWarehouseCodes Mã kho Tiki (ví dụ: ['sgn', 'hn4'])
     * @return array Kết quả trả về danh sách kho hàng và ánh xạ
     */
    public function getSellerInventories(array $tikiWarehouseCodes)
    {
        $warehouseCodes = implode(',', $tikiWarehouseCodes);

        $url = "v2/seller-inventories?tiki_warehouse_codes={$warehouseCodes}";

        return $this->call($url);
    }


    /**
     * Cập nhật trạng thái giao hàng cho đơn hàng seller delivery
     *
     * @param string $orderCode Mã đơn hàng cần cập nhật
     * @param string $status Trạng thái giao hàng ('successful_delivery' hoặc 'failed_delivery')
     * @param string|null $failureCause Nguyên nhân thất bại (nếu có)
     * @param string|null $appointmentDate Ngày hẹn giao lại (nếu failureCause là redelivery_appointment)
     * @param string|null $note Ghi chú giao hàng (nếu có)
     * @return array Kết quả trả về từ API
     */
    public function updateDeliveryStatus($orderCode, $status, $failureCause = null, $appointmentDate = null, $note = null)
    {
        $url = "integration/v2/orders/{$orderCode}/seller-delivery/update-delivery";

        $payload = [
            'status' => $status,
        ];

        if ($status === 'failed_delivery') {
            $payload['failure_cause'] = $failureCause;
            if ($failureCause === 'redelivery_appointment' && $appointmentDate) {
                $payload['appointment_date'] = $appointmentDate;
            }
            if ($note) {
                $payload['note'] = $note;
            }
        }

        return $this->call($url, $payload, 'POST');
    }


    /**
     * Cập nhật trạng thái vận chuyển cho đơn hàng cross-border
     *
     * @param string $orderCode Mã đơn hàng cần cập nhật
     * @param string $status Trạng thái vận chuyển
     * @param string $updateTime Thời gian cập nhật trạng thái
     * @return array Kết quả trả về từ API
     */
    public function updateShipmentStatus($orderCode, $status, $updateTime)
    {
        $url = "integration/v2/orders/{$orderCode}/cross-border/update-shipment";

        $payload = [
            'status' => $status,
            'update_time' => $updateTime
        ];

        return $this->call($url, $payload, 'POST');
    }


    /**
     * Lấy nhãn vận chuyển cho đơn hàng On-Demand Fulfillment
     *
     * @param string $orderCode Mã đơn hàng cần lấy nhãn vận chuyển
     * @param string $format Định dạng nhãn vận chuyển (ví dụ: html)
     * @return array Kết quả trả về từ API
     */
    public function getShippingLabel($orderCode, $format = 'html')
    {
        $url = "integration/v2/orders/{$orderCode}/tiki-delivery/labels?format={$format}";

        return $this->call($url);
    }

    /**
     * Lấy nhãn hóa đơn cho đơn hàng Seller Delivery
     *
     * @param string $orderCode Mã đơn hàng cần lấy nhãn hóa đơn
     * @param string $format Định dạng nhãn hóa đơn (ví dụ: html)
     * @return array Kết quả trả về từ API
     */
    public function getInvoiceLabel($orderCode, $format = 'html')
    {
        $url = "integration/v2/orders/{$orderCode}/seller-delivery/labels?format={$format}";

        return $this->call($url);
    }


    /**
     * Lấy nhãn giao hàng và dấu giao hàng cho đơn hàng Dropship
     *
     * @param string $orderCode Mã đơn hàng cần lấy nhãn giao hàng
     * @param string $format Định dạng nhãn (ví dụ: html)
     * @return array Kết quả trả về từ API
     */
    public function getShippingStamp($orderCode, $format = 'html')
    {
        $url = "integration/v2/orders/{$orderCode}/dropship/labels?format={$format}";

        return $this->call($url);
    }


    /**
     * Lấy nhãn giao hàng cho đơn hàng Cross Border
     *
     * @param string $orderCode Mã đơn hàng cần lấy nhãn giao hàng
     * @param string $format Định dạng nhãn (ví dụ: html)
     * @return array Kết quả trả về từ API
     */
    public function getCrossBorderLabel($orderCode, $format = 'html')
    {
        $url = "integration/v2/orders/{$orderCode}/cross-border/labels?format={$format}";

        return $this->call($url);
    }



}
