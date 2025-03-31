Nền Tảng Thương Mại Điện Tử với Laravel (MVC & Microservices)

Kho lưu trữ này giới thiệu một nền tảng thương mại điện tử được phát triển bằng Laravel, áp dụng kiến trúc MVC và microservices nhằm đảm bảo khả năng mở rộng và tính mô-đun. Dự án cung cấp một cơ sở vững chắc cho việc xây dựng các cửa hàng trực tuyến hiện đại với các chức năng thiết yếu như quản lý sản phẩm, giỏ hàng, xử lý đơn đặt hàng và tích hợp thanh toán.

Tính năng nổi bật:
Kiến trúc Microservice: Phân tách các chức năng cốt lõi (quản lý người dùng, kho hàng, thanh toán) thành các dịch vụ độc lập.

Khung MVC: Mã nguồn được tổ chức sạch sẽ và có hệ thống theo mô hình Model-View-Controller.

Thiết kế đáp ứng: Đảm bảo trải nghiệm người dùng mượt mà trên mọi thiết bị.

Môi trường Docker: Đơn giản hóa việc thiết lập và triển khai với Docker Compose.

Hướng dẫn bắt đầu:
Clone kho lưu trữ:

```bash
git clone https://github.com/ptd-seedam/ecommerce_laravel_shoptoy
```

Thiết lập môi trường: Cấu hình tệp .env với thông tin cơ sở dữ liệu và API của bạn.

Cài đặt các thư viện cần thiết:

```bash
composer install
npm install
```

Thực hiện migrate:

```bash
php artisan migrate
```

Khởi động server:

```bash
php artisan serve
```
