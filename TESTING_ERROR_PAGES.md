# Testing Error Pages di Laravel 12

## Cara Test Error Pages

### 1. Test Halaman Error Langsung

```
http://127.0.0.1:8000/404
http://127.0.0.1:8000/403
```

### 2. Test Error Handling dengan abort()

```
http://127.0.0.1:8000/test-404
http://127.0.0.1:8000/test-403
```

### 3. Test URL yang Tidak Ada

```
http://127.0.0.1:8000/halaman-yang-tidak-ada
```

### 4. Test Middleware Protection

1. Login sebagai mahasiswa
2. Akses: `http://127.0.0.1:8000/user`
3. Seharusnya redirect ke halaman 403

## Expected Results

### ✅ Halaman 404 Custom:

-   Judul: "We couldn't connect the dots"
-   Gambar: 404.png
-   Tombol: "Take me back to Home"

### ✅ Halaman 403 Custom:

-   Judul: "Access Denied"
-   Gambar: 403.png
-   Tombol: "Take me back to Home"

## Troubleshooting

### Jika halaman error tidak tampil:

1. **Clear Cache:**

    ```bash
    php artisan route:clear
    php artisan config:clear
    php artisan cache:clear
    ```

2. **Restart Server:**

    ```bash
    php artisan serve --host=127.0.0.1 --port=8000
    ```

3. **Cek File View:**

    - `resources/views/block/404.blade.php`
    - `resources/views/block/403.blade.php`

4. **Cek ErrorController:**

    - `app/Http/Controllers/ErrorController.php`

5. **Cek Bootstrap Config:**
    - `bootstrap/app.php` - exception handling

## Laravel 12 Specific

Di Laravel 12, exception handling dilakukan di `bootstrap/app.php` bukan di `app/Exceptions/Handler.php`.

### Konfigurasi Exception:

```php
->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
        return app(\App\Http\Controllers\ErrorController::class)->notFound();
    });
})
```

## Debug Commands

```bash
# Test ErrorController
php artisan tinker --execute="echo app('App\Http\Controllers\ErrorController')->notFound()->getStatusCode();"

# Cek Routes
php artisan route:list --name=error

# Cek Middleware
php artisan route:list --name=user
```
