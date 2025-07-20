# Testing Middleware dan Error Handling

## Cara Test Middleware

### 1. Test Akses Admin

1. Login sebagai admin
2. Akses `/user` → Seharusnya bisa akses
3. Akses `/prodi` → Seharusnya bisa akses
4. Akses `/dpa` → Seharusnya bisa akses

### 2. Test Akses Mahasiswa (Dilarang)

1. Login sebagai mahasiswa
2. Akses `/user` → Seharusnya redirect ke halaman 403
3. Akses `/prodi` → Seharusnya redirect ke halaman 403
4. Akses `/dpa` → Seharusnya redirect ke halaman 403

### 3. Test Error Pages

1. Akses `/404` → Seharusnya tampil halaman 404 custom
2. Akses `/403` → Seharusnya tampil halaman 403 custom
3. Akses URL yang tidak ada → Seharusnya redirect ke halaman 404 custom

## URL Testing

### Error Pages:

-   `http://127.0.0.1:8000/404` - Halaman 404 custom
-   `http://127.0.0.1:8000/403` - Halaman 403 custom

### Protected Routes (Admin Only):

-   `http://127.0.0.1:8000/user` - Manajemen User
-   `http://127.0.0.1:8000/prodi` - Manajemen Prodi
-   `http://127.0.0.1:8000/angkatan` - Manajemen Angkatan
-   `http://127.0.0.1:8000/mhs` - Manajemen Mahasiswa
-   `http://127.0.0.1:8000/dpa` - Manajemen DPA

### Public Routes:

-   `http://127.0.0.1:8000/dashboard` - Dashboard
-   `http://127.0.0.1:8000/akademik` - Data Akademik
-   `http://127.0.0.1:8000/prediksi` - Prediksi Kelulusan

## Expected Behavior

### Admin:

-   ✅ Akses semua halaman
-   ✅ Tidak ada redirect ke 403

### Mahasiswa:

-   ❌ Tidak bisa akses `/user`, `/prodi`, `/angkatan`, `/mhs`, `/dpa`
-   ✅ Redirect ke halaman 403 custom
-   ✅ Bisa akses dashboard, akademik, prediksi

### Error Handling:

-   ✅ 404 error tampil halaman custom
-   ✅ 403 error tampil halaman custom
-   ✅ JSON response untuk API calls
