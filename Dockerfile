# backend-laravel/Dockerfile

# Stage 1: Build dependencies và tối ưu hóa autoload. Đặt tên là "builder".
FROM composer:2.7 as builder

WORKDIR /app

# Copy toàn bộ source code của Laravel vào
COPY . .

# Chạy composer install
RUN composer install --no-interaction --no-dev --optimize-autoloader

# ---

# Stage 2: Build image cuối cùng để chạy ứng dụng
FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

# Cài đặt các thư viện hệ thống và extensions PHP cần thiết
# FIX: Cài đặt 'libpq' (runtime) và giữ lại, chỉ xóa 'postgresql-dev' (build-time).
RUN apk update && apk add --no-cache \
    libpq \
    postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql pcntl \
    && apk del postgresql-dev

# Copy source code từ thư mục hiện tại của bạn
COPY . .

# Copy thư mục "vendor" đã được build hoàn chỉnh từ stage "builder"
COPY --from=builder /app/vendor/ ./vendor/

# Phân quyền cho storage và bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000

# Chạy artisan serve
CMD php artisan serve --host=0.0.0.0 --port=8000