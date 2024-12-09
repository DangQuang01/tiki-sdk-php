<?php
namespace Dangquang\TikiPhp;

class Config
{
    // Biến static để lưu cấu hình, chỉ đọc một lần
    private static $config = null;

    // Hàm để lấy giá trị cấu hình theo key
    public static function get($key)
    {
        // Nếu cấu hình chưa được tải thì load nó
        if (self::$config === null) {
            self::$config = require __DIR__ . '/../config/tiki.php'; 
        }
        
        return self::$config[$key] ?? null;
    }
}

