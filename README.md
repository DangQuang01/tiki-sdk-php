# Tiki SDK PHP

Tiki Client is a simple SDK implementation of Tiki API by QuangCode7Mau.

## Cài đặt

Để sử dụng Tiki SDK trong dự án PHP của bạn, bạn có thể cài đặt package này thông qua Composer.

### Yêu cầu hệ thống

- PHP >= 7.4
- Composer
- GuzzleHTTP 7.9 trở lên

### Cài đặt thông qua Composer

Để cài đặt package này, bạn chỉ cần chạy lệnh Composer sau:

```bash
composer require dangquang/tiki-sdk-php
```

### Cấu hình

Trước khi sử dụng Tiki SDK, bạn cần cấu hình một số thông tin quan trọng, bao gồm API Key, API Secret và Access Token. Bạn có thể cấu hình chúng trong file `.env` của Laravel hoặc bằng cách sử dụng các tham số trong mã nguồn của bạn.

**1. Tạo một đối tượng Client**

Bạn cần tạo một đối tượng `Client` bằng cách truyền vào `apiKey` và `apiSecret` của bạn. Đây là thông tin mà bạn nhận được khi đăng ký và tạo ứng dụng trên Tiki.

```php
use Dangquang\TikiPhp\Client;

// Khởi tạo Client với API Key và API Secret
$client = new Client('your-api-key', 'your-api-secret');
```
