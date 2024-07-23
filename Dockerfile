# استخدم صورة PHP الأساسية
FROM php:8.2-apache

# تحديث الحزم وتثبيت Composer
RUN apt-get update && apt-get install -y \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

# نسخ ملفات التطبيق إلى مجلد العمل في الصورة
COPY . /var/www/html

# نسخ Composer من الحاسوب إلى الصورة
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تشغيل Composer لتثبيت التبعيات
RUN cd /var/www/html && composer install

# تعيين إذونات مناسبة لمجلد العمل
RUN chown -R www-data:www-data /var/www/html

# فتح المنفذ 80 للتطبيق
EXPOSE 80

# بدء تشغيل Apache
CMD ["apache2-foreground"]
