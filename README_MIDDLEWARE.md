# Middleware Role dan Error Handling

## Overview

Sistem ini telah dilengkapi dengan middleware untuk mengontrol akses berdasarkan role dan error handling untuk halaman 404 dan 403.

## Middleware Role

### RoleMiddleware

Middleware ini mengontrol akses berdasarkan role user. Berada di `app/Http/Middleware/RoleMiddleware.php`

#### Fitur:

-   Mengecek apakah user sudah login
-   Memvalidasi role user sebelum mengizinkan akses
-   Redirect ke halaman 403 jika user tidak memiliki akses
-   Redirect ke dashboard dengan pesan error jika role tidak sesuai

#### Penggunaan:

```php
// Di routes/web.php
Route::group(['prefix' => 'user', 'middleware' => 'role:admin'], function () {
    // Routes hanya untuk admin
});
```

## Error Handling

### Handler.php

Exception handler yang menangani error 404 dan 403. Berada di `app/Exceptions/Handler.php`

#### Fitur:

-   Menangani error 404 dengan halaman custom `block.404`
-   Menangani error 403 dengan halaman custom `block.403`
-   Support untuk response JSON dan HTML

### Halaman Error Custom

-   **404 Error**: `resources/views/block/404.blade.php`
-   **403 Error**: `resources/views/block/403.blade.php`

## Routes yang Dilindungi

### Hanya untuk Admin:

-   `/user/*` - Manajemen User
-   `/prodi/*` - Manajemen Program Studi
-   `/angkatan/*` - Manajemen Angkatan
-   `/mhs/*` - Manajemen Mahasiswa
-   `/dpa/*` - Manajemen DPA

### Untuk Semua Role:

-   `/dashboard` - Dashboard
-   `/akademik/*` - Data Akademik (terbatas berdasarkan role)
-   `/prediksi/*` - Prediksi Kelulusan (terbatas berdasarkan role)

## Cara Kerja

1. **User Login**: Middleware mengecek apakah user sudah login
2. **Role Validation**: Jika route memerlukan role tertentu, middleware memvalidasi role user
3. **Access Control**:
    - Jika role sesuai → Lanjut ke halaman
    - Jika role tidak sesuai → Redirect ke 403 atau dashboard
4. **Error Handling**: Jika terjadi error 404/403, sistem menampilkan halaman custom

## Testing

Untuk test middleware:

1. Login sebagai mahasiswa
2. Coba akses `/user` atau `/prodi`
3. Seharusnya redirect ke halaman 403

Untuk test error handling:

1. Akses URL yang tidak ada → 404
2. Akses halaman tanpa permission → 403
