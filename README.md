
# HIKARI

## Yêu Cầu Hệ Thống
- PHP >= 7.0.0
- Composer
- Node.js & NPM
- MySQL

## Hướng Dẫn Cài Đặt

### 1. Sao chép tệp `.env` từ `.env.example`
Đầu tiên, sao chép tệp `.env.example` thành `.env`:
```bash
cp .env.example .env
```
Sau đó, mở file `.env` và điều chỉnh các giá trị cần thiết cho môi trường của bạn, ví dụ: cấu hình cơ sở dữ liệu.

### 2. Cài đặt các phụ thuộc PHP
Chạy lệnh sau để cài đặt tất cả các gói PHP yêu cầu thông qua Composer:

```bash
composer install
```
Hoặc nếu đã có tệp `composer.lock`, bạn có thể sử dụng:
```bash
composer update
```

### 3. Cài đặt các gói JavaScript
Tiếp theo, cài đặt các gói JavaScript cần thiết bằng cách chạy lệnh:

```bash
npm install
```

### 4. Tạo tài liệu Swagger
Để tạo tài liệu API Swagger, chạy lệnh sau:

```bash
php artisan l5-swagger:generate
```
Sau đó, bạn có thể truy cập tài liệu Swagger tại đường dẫn:
```
http://your-app-url/api/documentation
```

### 5. Khởi động ứng dụng
Sau khi cài đặt, bạn có thể khởi động ứng dụng Laravel bằng lệnh:
```bash
php artisan serve
```
Ứng dụng sẽ chạy tại `http://localhost:8000`.

## Các Lệnh Hữu Ích

- **Xóa cache cấu hình**:
  ```bash
  php artisan config:clear
  ```

- **Xóa cache route**:
  ```bash
  php artisan route:clear
  ```

- **Xóa cache view**:
  ```bash
  php artisan view:clear
  ```

- **Xóa tất cả cache**:
  ```bash
  php artisan optimize:clear
  ```