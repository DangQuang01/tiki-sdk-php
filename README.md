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

Bạn cần tạo một đối tượng `Client` bằng cách truyền vào `appID` và `appSecret` của bạn. Đây là thông tin mà bạn nhận được khi đăng ký và tạo ứng dụng trên Tiki.

```php
use Dangquang\TikiPhp\Client;

// Khởi tạo Client với API Key và API Secret
$client = new Client('your-app-id', 'your-app-secret');
```

### Authentication

Để sử dụng OAuth2 với Tiki API, làm theo các bước dưới đây.

**1. Tạo URL xác thực**

Chuyển hướng người dùng đến trang đăng nhập của Tiki:

```php
use Dangquang\TikiPhp\Auth;

// Khởi tạo Auth
$auth = new Auth($client);

// Tạo URL yêu cầu xác thực
$redirectUri = 'https://yourapp.com/callback';
$auth->createAuthRequest($redirectUri);
```

**2. Lấy mã token**

Sau khi người dùng xác thực, sử dụng mã code nhận được để lấy access token:

```php
$code = $_GET['code'];
$state = $_GET['state'];
$auth->getToken($code, $redirectUri, $state);
```

**3. Làm mới token**

Sử dụng refresh_token để lấy mã truy cập mới khi hết hạn:

```php
$refreshToken = 'your-refresh-token';
$newAccessToken = $auth->refreshToken($refreshToken);
```

**4. Lấy mã token thông qua Client Credentials**

Lấy access token mà không cần người dùng xác thực:

```php
$accessToken = $auth->getClientCredentialsToken();
```

## Ví dụ

### Authentication

> Dưới đây là ví dụ về cách sử dụng các phương thức trong lớp Order để lấy danh sách đơn hàng và chi tiết đơn hàng trong Tiki SDK PHP:

```php
use Dangquang\TikiPhp\Client;

$client = new Client('your-app-id', 'your-app-secret');

$client->setAccessToken('your-access-token');

$client->order->getOrderList();

$client->Shop->getSellerInfo();
```
