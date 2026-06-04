# Panduan Instalasi dan Menjalankan Aplikasi

Aplikasi BubbleWash menggunakan framework Laravel 12. Berikut adalah langkah-langkah untuk menjalankan aplikasi secara lokal di komputer Anda.

## Persyaratan (Requirements)
- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite (terpaket dengan PHP, tidak perlu install server DB terpisah) atau MySQL.

## Langkah-langkah Instalasi

1. **Buka Terminal / Command Prompt**
   Navigasikan ke dalam folder proyek.
   ```bash
   cd "C:\SEMESTER 4\pemrograman web\UAS-LAUNDRY"
   ```

2. **Install Dependensi PHP**
   Jalankan composer untuk mengunduh semua vendor package.
   ```bash
   composer install
   ```

3. **Install Dependensi Node/NPM**
   Jalankan npm untuk mengunduh library frontend (Tailwind CSS v4, dll).
   ```bash
   npm install
   ```

4. **Konfigurasi Lingkungan (.env)**
   Secara default file `.env` sudah dikonfigurasi untuk proyek ini (menggunakan SQLite).
   Jika Anda memindahkan proyek ke komputer lain dan file `.env` hilang, Anda dapat menduplikat dari `.env.example`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Pastikan konfigurasi DB pada file `.env` mengarah ke SQLite (sudah di set secara default dalam tutorial ini):
   ```env
   DB_CONNECTION=sqlite
   ```

5. **Migrasi Database & Seeding**
   Jalankan perintah berikut untuk membuat struktur database (tabel) dan mengisi data awal (layanan dan admin).
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Perintah di atas akan secara otomatis memicu pembuatan `database/database.sqlite` jika belum ada).*

6. **Kompilasi Aset Frontend (Tailwind)**
   Buka terminal/tab baru dan biarkan perintah ini berjalan untuk melayani CSS.
   ```bash
   npm run dev
   ```

7. **Jalankan Laravel Development Server**
   Di terminal utama Anda, jalankan server lokal.
   ```bash
   php artisan serve
   ```
   Aplikasi sekarang dapat diakses di browser melalui URL: `http://localhost:8000`

---

## Testing / Pengujian Aplikasi
- **Registrasi Akun Baru**: Buat akun melalui halaman Registrasi.
- **Simulasi OTP**: Setelah mendaftar, klik "Kirim Kode OTP (Simulasi)". Kode OTP akan muncul di bagian atas layar sebagai pesan notifikasi berwarna pink. Masukkan kode tersebut.
- **Pesan Laundry**: Masuk ke menu Layanan, tambah ke Keranjang, lalu masuk ke Checkout.
- **Testing Lokasi Map**:
  - Peta terpusat di tengah Yogyakarta. Area dalam lingkaran pink beradius 3 KM.
  - Coba klik **di dalam** lingkaran pink, tombol checkout akan menyala.
  - Coba klik **jauh di luar** lingkaran pink, peringatan berwarna merah akan muncul dan tombol checkout akan disable.
- **Track Pesanan**: Copy Nomor TRX dari invoice, lalu pergi ke Beranda -> Cek Status (Gunakan nomor HP yg sama - 5 digit terakhir).
