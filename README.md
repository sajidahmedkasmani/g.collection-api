# G. Collection Laravel API overlay

This is the backend source overlay for a fresh Laravel 12 application. It is not a standalone Laravel skeleton: first create the Laravel app on a computer with Composer, then copy these files into it.

## 1) Create Laravel app

```bash
composer create-project laravel/laravel gcollection-api "^12.0"
cd gcollection-api
php artisan install:api
```

Copy the contents of this `laravel-api-overlay` folder into the Laravel app, merging folders and replacing `routes/api.php`. Keep Laravel's existing `composer.json`, `.env`, and `bootstrap/app.php`.

## 2) Configure MySQL in Laravel `.env`

```env
APP_NAME="G. Collection API"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.gcollection.com
FRONTEND_ORIGINS=https://gcollection.com,https://www.gcollection.com
DB_CONNECTION=mysql
DB_HOST=YOUR_HOSTINGER_DB_HOST
DB_PORT=3306
DB_DATABASE=YOUR_DATABASE_NAME
DB_USERNAME=YOUR_DATABASE_USER
DB_PASSWORD=YOUR_DATABASE_PASSWORD
```

Use the actual domain and database values from Hostinger. Never put DB credentials in Next.js or public environment variables.

## 3) Register API routes (Laravel 12)

After `php artisan install:api`, open `bootstrap/app.php` and ensure `withRouting` includes the API route file, typically:

```php
api: __DIR__.'/../routes/api.php',
```

Keep the other existing routing arguments. Ensure `config/cors.php` from this overlay is copied into the app.

## 4) Run migrations and seed the 10 catalogue products

```bash
php artisan migrate --force
php artisan db:seed --class=ProductSeeder --force
php artisan config:clear
```

## 5) API endpoints

- `GET /api/health`
- `GET /api/products`
- `GET /api/products/{id}` (Laravel internal product ID)
- `POST /api/orders`

Order request example:

```json
{
  "customer_name": "Test Customer",
  "email": "customer@example.com",
  "phone": "03001234567",
  "address": "House 10, Street 2",
  "city": "Karachi",
  "payment_method": "cod",
  "items": [{"product_id": 29065, "quantity": 1}]
}
```

For the order endpoint, `product_id` is the existing catalogue's external WooCommerce ID. The backend re-reads current prices from MySQL; it does not trust frontend prices. Payment methods are `cod` and `bank_transfer`. The backend stores the order and item lines transactionally.

## Important before public launch

- Set `APP_DEBUG=false`, use HTTPS, strong DB credentials, and restrict CORS to the exact production frontend origin.
- Add an authenticated admin interface and order-status workflow before offering self-service order management. This overlay does not yet include an admin panel.
- Confirm shipping rules, bank-transfer account details, privacy/returns policies, and WhatsApp business number before launch.
- Test a real test order end-to-end. The source package is not deployed to your Hostinger account by this ZIP.
