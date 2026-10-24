# Sistem Gudang (Laravel)

Pencatatan supply barang (masuk / ekspor), stok, dan kehadiran karyawan harian.
Dikonversi dari aplikasi PHP satu-file (`index.php`) ke struktur Laravel 12.

## Menjalankan

```bash
composer install
php artisan migrate --seed     # buat tabel + akun awal + data contoh
php artisan serve
```

Pastikan database `gudang_db` sudah dibuat dan kredensial MySQL di `.env` sesuai
(`DB_USERNAME`, `DB_PASSWORD`). Jika `.env` belum ada: `cp .env.example .env && php artisan key:generate`.

Akun awal (dari `PenggunaSeeder`):

| Username | Password   | Hak akses |
|----------|------------|-----------|
| admin    | admin123   | Pengelola (semua menu) |
| staf     | staf123    | User (lihat supply, isi daftar hadir) |

Ganti password setelah login pertama.

## Peta file lama → baru

| Aplikasi lama | Laravel |
|---|---|
| `config.php` (PDO) | `.env` + `config/database.php` |
| `database.sql` | `database/migrations/*` + `database/seeders/*` |
| Login, session, CSRF | `AuthController`, middleware `auth`, `@csrf` |
| Array `$E` (definisi modul) | `config()` di tiap controller, basis `CrudController` |
| `izin()` / `HAK_USER` | `User::izin()` / `User::HAK_USER` |
| Cek stok ekspor | `Supply::stok()` + `SupplyController::validasiTambahan()` |
| HTML & CSS inline | `resources/views/*` + `public/css/app.css` |

## Menambah modul baru

1. Buat migration + model di `app/Models`.
2. Buat controller turunan `CrudController` (isi `modul`, `modelClass`, `baseQuery`, `config`).
3. Daftarkan di `routes/web.php` dan `config/gudang.php`.

## Tes

```bash
php artisan test
```
