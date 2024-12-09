<?php

namespace Dangquang\TikiPhp\Resources;

use Dangquang\TikiPhp\Resource;
use Dangquang\TikiPhp\Config;

class Product extends Resource
{
    protected $product_endpoint;
    protected $category_endpoint;

    public function __construct()
    {
        $this->product_endpoint = Config::get('product_endpoint');
        $this->category_endpoint = Config::get('category_endpoint');
    }

    /**
     * Lấy danh sách các danh mục từ Tiki
     *
     * @param string $name Tìm kiếm theo một phần tên danh mục (không bắt buộc)
     * @param int $parent ID danh mục cha (không bắt buộc)
     * @param bool $isPrimary Kiểm tra xem danh mục có phải là danh mục chính không (không bắt buộc)
     * @param bool $isCrossBorder Kiểm tra xem danh mục có phải là cross-border không (không bắt buộc)
     * @return array Kết quả trả về từ API
     */
    public function getCategories($name = '', $parent = null, $isPrimary = null, $isCrossBorder = false)
    {
        $params = [];

        if ($name) {
            $params['name'] = $name;
        }
        if ($parent !== null) {
            $params['parent'] = $parent;
        }
        if ($isPrimary !== null) {
            $params['isPrimary'] = $isPrimary ? 'true' : 'false';
        }
        if ($isCrossBorder) {
            $params['isCrossBorder'] = 'true';
        }

        $url = Config::get('category_endpoint') . '?' . http_build_query($params);

        return $this->call($url);
    }

    /**
     * Lấy chi tiết danh mục từ Tiki
     *
     * @param int $categoryId ID của danh mục
     * @param bool $includeParents Có bao gồm các danh mục cha hay không (mặc định là false)
     * @return array Kết quả trả về từ API
     */
    public function getCategoryDetail($categoryId, $includeParents = false)
    {
        $params = $includeParents ? ['includeParents' => 'true'] : [];

        $url = $this->category_endpoint . "{$categoryId}?" . http_build_query($params);

        return $this->call($url);
    }


    public function getCategoryAttributes($categoryId)
    {
        $url = $this->category_endpoint . "/{$categoryId}/attributes";

        return $this->call($url);
    }


    public function getAttributeValues($attributeId, $q = '', $page = 1, $limit = 20)
    {
        $params = [
            'q' => $q,
            'page' => $page,
            'limit' => $limit
        ];

        $params = array_filter($params, function ($value) {
            return $value !== '';
        });

        $url = "integration/v2/attributes/{$attributeId}/values?" . http_build_query($params);

        return $this->call($url);
    }

    public function getOptionAttributeLabels($categoryId)
    {
        $url = $this->category_endpoint . "/{$categoryId}/optionLabels";

        return $this->call($url);
    }

}