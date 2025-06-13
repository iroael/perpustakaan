# 📚 Aplikasi Peminjaman Buku Perpustakaan (Laravel)

Sistem informasi perpustakaan berbasis Laravel untuk manajemen data buku, anggota, peminjaman, dan pengembalian. Aplikasi ini mendukung dashboard statistik, proses peminjaman dengan pengurangan stok, serta pengembalian dengan pencatatan status keterlambatan.

## 🚀 Fitur Utama

- ✅ Manajemen Buku (CRUD)
- ✅ Manajemen Anggota (CRUD)
- ✅ Peminjaman Buku (dengan pilihan banyak buku)
- ✅ Pengembalian Buku (parsial atau semua)
- ✅ Otomatis update stok buku
- ✅ Validasi keterlambatan dan denda
- ✅ Dashboard grafik peminjaman 6 bulan terakhir
- ✅ DataTables untuk daftar peminjaman
- ✅ Ajax untuk proses penyimpanan dan penghapusan

## 🛠️ Teknologi

- PHP 8+
- Laravel 10
- PostgreSQL
- Tailwind CSS + Blade Components
- jQuery + DataTables
- Carbon (handling tanggal)
- Laravel Eloquent ORM

## ⚙️ Instalasi

```bash
# Clone repository
git clone https://github.com/iroeal/perpustakaan.git
cd perpustakaan

# Install dependensi
composer install
npm install

# Salin dan konfigurasi file environment
cp .env.example .env
php artisan key:generate

# Buat database lalu sesuaikan DB_* di file .env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5433           # GANTI PORT sesuai PostgreSQL kamu
DB_DATABASE=perpustakaan
DB_USERNAME=postgres
DB_PASSWORD=secret

# Jalankan migrasi dan seeder (opsional)
php artisan migrate

# Jalankan aplikasi
php artisan serve
