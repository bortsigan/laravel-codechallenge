## Laravel Code Challenge 

### Setup
- clone the repo https://github.com/bortsigan/laravel-codechallenge.git
- go to the project folder
- cp .env.example .env
- setup the DB configuration
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=*******
DB_USERNAME=*******
DB_PASSWORD=*******
DB_SOCKET=********
```
- composer install
- php artisan serve


### Routes
- api/login
- api/logout
- api/register
- api/voucher/delete/{voucher_code}
- api/vouchers
- api/vouchers/generate


### Run test (VoucherController)
- php artisan test

