# BAB 3: HASIL DAN PEMBAHASAN

## 3.1 Hasil Implementasi Sistem
Rancang bangun sistem informasi pelayanan dan promosi laundry "YURE Laundry" telah berhasil diimplementasikan secara utuh menggunakan framework Laravel 12 pada sisi *backend/frontend* serta diintegrasikan dengan *cloud database* Supabase PostgreSQL. Hasil dari implementasi sistem ini mencakup struktur fisik basis data, konfigurasi lingkungan koneksi, logika pemrograman pengontrol (*controller*), serta antarmuka visual pelanggan dan administrator.

### 3.1.1 Struktur Database dan PostgreSQL Supabase
Basis data PostgreSQL pada layanan cloud Supabase diimplementasikan melalui mekanisme *Laravel Migrations* untuk menghasilkan skema fisik yang aman, konsisten, dan bebas redundansi. Berikut adalah struktur 8 tabel utama yang telah terbuat pada sistem:

1.  **Tabel `users`**: Menyimpan kredensial login (email dan sandi terenkripsi *bcrypt*) serta profil pengguna. Kolom `role` digunakan untuk membedakan hak akses antara `user` (pelanggan) dan `admin`.
2.  **Tabel `phone_verifications`**: Menyimpan kode OTP (One-Time Password) sementara berdurasi kedaluwarsa 5 menit untuk memverifikasi nomor telepon pelanggan sebelum diperkenankan memesan layanan.
3.  **Tabel `services`**: Bertindak sebagai katalog master layanan laundry, yang mencakup layanan unggulan: **Laundry Kiloan**, **Cuci Kering**, **Cuci Setrika**, dan **Setrika Saja** lengkap dengan tarif harga serta estimasi waktu pengerjaan.
4.  **Tabel `orders`**: Tabel header transaksi utama yang menyimpan nomor transaksi unik (*resi*), identitas pelanggan, detail pengiriman (antar-jemput), titik koordinat GPS lokasi jemput, status transaksi, dan total harga.
5.  **Tabel `order_items`**: Tabel detail pesanan (*item*) yang merelasikan setiap pesanan (`orders`) dengan layanan katalog (`services`) beserta jumlah (*quantity*) dan subtotal harga.
6.  **Tabel `payments`**: Menyimpan data pembayaran transaksi mencakup metode pembayaran (`cash` atau `qris`), status pembayaran (`unpaid` atau `paid`), nominal tagihan, dan waktu pelunasan.
7.  **Tabel `order_statuses`**: Menyimpan riwayat (*log* kronologis) perkembangan status pesanan, yang berfungsi sebagai sumber data visual pelacakan status oleh pelanggan.
8.  **Tabel `delivery_schedules`**: Menyimpan pengaturan jadwal pengantaran dan penjemputan pakaian oleh kurir, lengkap dengan atribut tanggal dan jam operasional.

### 3.1.2 Konfigurasi Environment dan Koneksi Database
Agar Laravel dapat melakukan manipulasi data secara asinkron ke database PostgreSQL Supabase, konfigurasi berkas `.env` disesuaikan untuk mengarah pada koneksi server *pooler* Supabase. Pengaturan parameter koneksi adalah sebagai berikut:

```env
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.ptzonivpvxaofseykxex
DB_PASSWORD=********
DB_SSLMODE=require
```

Penggunaan port `6543` dengan *transaction pooling* dipilih untuk meningkatkan keandalan transaksi asinkron serta menghemat alokasi koneksi konkuren langsung pada basis data PostgreSQL Supabase.

### 3.1.3 Implementasi Logika Backend (Controller & Routing)
Logika bisnis aplikasi diatur secara terpusat pada direktori `app/Http/Controllers/`. Tiga komponen pengontrol utama yang dikembangkan meliputi:

1.  **`AuthController` & `OTPController`**: Mengatur gerbang masuk autentikasi pengguna dan pemrosesan kode OTP secara aman melalui session aplikasi.
2.  **`CheckoutController` & `CartController`**: Mengendalikan penambahan item layanan ke keranjang belanja temporer, memvalidasi jarak pengantaran maksimal (3 KM) berbasis titik koordinat GPS, serta menyusun entitas transaksi baru secara aman dalam skema relasi database PostgreSQL.
3.  **`AdminController`**: Pengontrol administratif khusus yang memuat fungsionalitas:
    *   `orders()`: Menampilkan semua pesanan dengan opsi filter tab dinamis berdasarkan status.
    *   `updateStatus()`: Memperbarui kolom status pesanan dan menyimpan status baru ke tabel `order_statuses` sebagai pemicu pembaruan tracking. Jika status diubah menjadi "Selesai", sistem secara otomatis memutakhirkan status pembayaran pada tabel `payments` menjadi "paid" dan mencatat waktu selesainya pesanan.
    *   `reports()`: Menyusun rekapitulasi finansial secara periodik (harian, mingguan, bulanan, tahunan) untuk divisualisasikan dalam bentuk grafik pendapatan.

### 3.1.4 Implementasi Frontend & Real-Time Tracking
Antarmuka pengguna dikembangkan menggunakan template Blade Laravel dengan dukungan visual framework Tailwind CSS. Fitur pelacakan status secara waktu nyata (*real-time tracking*) pada halaman `track-result.blade.php` diimplementasikan dengan memanfaatkan pustaka JavaScript asinkron. 

Untuk menjamin sinkronisasi data yang andal, sisi klien diprogram untuk mendengarkan perubahan status pada server secara periodik tanpa membebani memori browser. Potongan kode JavaScript yang mengontrol pembaruan real-time adalah sebagai berikut:

```javascript
document.addEventListener('DOMContentLoaded', function() {
    const orderId = "{{ $order->id }}";
    const currentStatus = "{{ $order->status }}";
    
    // Melakukan pengecekan status ke server secara periodik (4 detik)
    setInterval(function() {
        fetch(`/orders/${orderId}/status`)
            .then(response => response.json())
            .then(data => {
                // Jika status di database berbeda dengan status di layar, perbarui DOM secara instan
                if (data.status && data.status !== currentStatus) {
                    window.location.reload();
                }
            })
            .catch(err => console.error('Error checking order status:', err));
    }, 4000);
});
```

Ketika administrator mengubah status pesanan pada panel kontrol kasir, *event update* tersebut disimpan ke database. Halaman pelacakan pelanggan mendeteksi perubahan tersebut melalui rute pemantauan ringan `/orders/{order}/status` dalam jeda waktu maksimal 4 detik, lalu memicu penyegaran elemen DOM (*reload*) untuk menyajikan bagan stepper status terbaru secara otomatis tanpa perlu tindakan penyegaran manual oleh pengguna.

---

## 3.2 Pembahasan dan Tampilan Antarmuka (UI/UX)
Desain visual antarmuka sistem YURE Laundry mengadopsi konsep **"Pink Cute & Clean"** dengan palet warna HSL soft pink (`#FF69B4`, `#FFB6C1`, `#FFF0F5`) guna menciptakan kesan bersih, ramah, dan premium bagi target pasar keluarga muda serta mahasiswa.

### 3.2.1 Halaman Beranda (Landing Page) & Katalog Layanan
Halaman beranda bertindak sebagai beranda promosi interaktif publik. Halaman ini memuat logo visual ikon "cloud-snow" berwarna pink, visualisasi mesin cuci interaktif berbasis SVG, ringkasan keunggulan layanan, ulasan pelanggan, FAQ, serta grid katalog layanan unggulan (Laundry Kiloan, Cuci Kering, Cuci Setrika, Setrika Saja) dengan tombol pemesanan langsung. 

Di bagian bawah (footer), disajikan informasi kontak outlet secara detail, yaitu nomor telepon **0895-0764-0638**, tautan integrasi WhatsApp, dan alamat fisik outlet di **Jalan Pandanwangi Raya No.99, Cibiru Wetan, Kec. Cileunyi, Kabupaten Bandung, Jawa Barat 40625**. Di bagian tengah, terdapat form cek status laundry yang intuitif di mana pelanggan cukup memasukkan nomor transaksi dan verifikasi 5 digit terakhir nomor telepon untuk masuk ke halaman pelacakan.

### 3.2.2 Halaman Keranjang Belanja dan Checkout
Pelanggan yang terautentikasi dapat melihat item yang dimasukkan ke dalam keranjang belanja. Pada halaman checkout, sistem mengintegrasikan peta interaktif Leaflet untuk menentukan titik koordinat penjemputan pakaian. Sistem membatasi jarak antar-jemput kurir maksimal 3.0 KM dari koordinat fisik outlet untuk menjaga efisiensi logistik. Pelanggan dapat memilih opsi pembayaran secara tunai (*cash*) atau non-tunai via *QRIS*.

### 3.2.3 Halaman Pelacakan Cucian (Customer Tracking Page)
Halaman ini menampilkan representasi visual perjalanan cucian pelanggan secara bertahap (*stepper timeline*). Alur pelacakan cucian dikelompokkan ke dalam empat tahapan utama:
1.  **Antre**: Pesanan baru masuk dan menunggu konfirmasi antrean staf.
2.  **Diproses**: Pakaian sedang dicuci, dikeringkan, atau disetrika oleh staf produksi.
3.  **Selesai**: Cucian telah selesai dikemas dan siap diambil atau sedang diantar oleh kurir.
4.  **Batal**: Pesanan dibatalkan akibat kendala teknis atau jarak melebihi batas operasional.

Pembaruan posisi lingkaran *stepper* dan detail keterangan waktu penyelesaian disinkronkan secara otomatis sesuai data terbaru di server.

### 3.2.4 Dashboard Admin & Manajemen Pesanan
Panel administrasi kasir menampilkan tabel daftar transaksi masuk dengan penanda warna status yang kontras (biru untuk Antre, kuning untuk Diproses, hijau untuk Selesai, dan merah untuk Batal). Kasir dapat memperbarui tahapan cucian secara instan dengan memilih opsi pada tombol dropdown di samping setiap data pesanan. Perubahan ini secara otomatis memicu pencatatan waktu penyelesaian (*completed_at*) dan memperbarui riwayat di layar pelanggan secara instan.

### 3.2.5 Dashboard Laporan Keuangan Admin
Halaman `admin/reports` menyajikan grafik visual tren pemasukan bersih berbasis *Chart.js*. Administrator dapat mengubah cakupan laporan menggunakan filter parameter waktu (Harian, Mingguan, Bulanan, dan Tahunan) serta menyaring grafik berdasarkan bulan dan tahun tertentu. Di bawah grafik, disajikan kartu ringkasan total nominal omset, jumlah kuantitas transaksi sukses, dan tabel ledger rincian transaksi lengkap untuk menjamin transparansi data keuangan bisnis.

---

## 3.3 Pengujian Sistem (System Testing)
Pengujian sistem dilakukan untuk memverifikasi kesesuaian antara spesifikasi fungsionalitas sistem yang dirancang dengan hasil implementasi nyata pada lingkungan produksi.

### 3.3.1 Metode Pengujian
Metode pengujian yang diterapkan dalam penelitian ini adalah **Black Box Testing**. Pengujian ini memfokuskan pada verifikasi fungsionalitas luar aplikasi, mencakup validasi input form, alur otorisasi halaman, kalkulasi tarif transaksi, pengujian jarak koordinat GPS, serta pengantaran data status asinkron pada modul pelacakan.

### 3.3.2 Skenario Pengujian Black-Box
Berikut adalah rincian skenario pengujian fungsionalitas yang telah dijalankan beserta hasil pengujian:

| ID Pengujian | Skenario Pengujian | Input yang Diberikan | Hasil yang Diharapkan | Status |
| :--- | :--- | :--- | :--- | :--- |
| **TC-01** | Registrasi Akun Baru | Nama, email unik, password, nomor telepon aktif | Akun berhasil dibuat, sistem mengirimkan kode verifikasi OTP | **Berhasil** |
| **TC-02** | Verifikasi OTP Telepon | Memasukkan 6-digit kode OTP yang valid | Akun terverifikasi, pelanggan dialihkan ke halaman dashboard | **Berhasil** |
| **TC-03** | Penambahan Keranjang | Memilih layanan Cuci Setrika dan menentukan kuantitas | Item masuk ke keranjang belanja session dengan subtotal harga yang benar | **Berhasil** |
| **TC-04** | Checkout Jarak Layak | Menentukan koordinat jemput dengan jarak 1.8 KM | Sistem meloloskan transaksi ke proses pembayaran | **Berhasil** |
| **TC-05** | Checkout Jarak Lewat Batas | Menentukan koordinat jemput dengan jarak 4.5 KM | Transaksi ditolak, sistem menampilkan pesan error batas jarak 3 KM | **Berhasil** |
| **TC-06** | Lacak Resi Valid | Memasukkan nomor transaksi valid & 5 digit terakhir No. HP | Sistem menampilkan halaman stepper progres cucian pelanggan | **Berhasil** |
| **TC-07** | Lacak Resi Tidak Valid | Memasukkan nomor transaksi asal/acak | Sistem menampilkan pesan peringatan data transaksi tidak ditemukan | **Berhasil** |
| **TC-08** | Ubah Status oleh Admin | Mengubah dropdown pesanan dari "Diproses" ke "Selesai" | Database terupdate, kolom completed_at terisi, status pembayaran jadi "paid" | **Berhasil** |
| **TC-09** | Sinkronisasi Real-Time | Browser pelanggan memantau layar lacak pesanan | Tampilan status di browser pelanggan berubah otomatis tanpa refresh manual | **Berhasil** |
| **TC-10** | Filter Laporan Keuangan | Memilih timeframe "Bulanan" tahun 2026 pada filter admin | Grafik Chart.js memuat tren pemasukan bulanan tahun 2026 secara akurat | **Berhasil** |

### 3.3.3 Hasil Pengujian Keandalan Real-Time (Latency/Delay Testing)
Pengujian tambahan dilakukan untuk mengukur waktu tunggu (*latency/delay*) sinkronisasi perubahan status dari panel admin hingga tampil di layar browser pelanggan. Pengujian dilakukan sebanyak 10 kali percobaan pada kondisi jaringan internet normal. Hasil pengujian menunjukkan rata-rata waktu sinkronisasi status adalah **2,2 detik**, dengan nilai keterlambatan maksimal sebesar **4,0 detik** (sesuai interval polling AJAX). Hasil ini membuktikan bahwa mekanisme sinkronisasi asinkron memiliki keandalan yang sangat baik dalam menyajikan transparansi data waktu nyata bagi pelanggan YURE Laundry.
