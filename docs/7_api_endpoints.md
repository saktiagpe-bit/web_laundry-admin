# Web Routes / Endpoints

Meskipun ini adalah aplikasi berbasis Blade (bukan REST API JSON murni), berikut adalah daftar _route/endpoint_ standar yang digunakan oleh sistem beserta aksinya.

| Method | Endpoint | Controller | Deskripsi / Fungsi |
| --- | --- | --- | --- |
| **GET** | `/` | `LandingController@index` | Menampilkan Landing Page |
| **POST** | `/track` | `LandingController@track` | Melacak status pesanan menggunakan TRX ID |
| **GET** | `/login` | `AuthController@showLoginForm` | Menampilkan form login |
| **POST** | `/login` | `AuthController@login` | Memproses login pengguna |
| **GET** | `/register` | `AuthController@showRegistrationForm`| Menampilkan form registrasi |
| **POST** | `/register` | `AuthController@register` | Memproses pendaftaran user baru |
| **POST** | `/logout` | `AuthController@logout` | Mengakhiri sesi pengguna |
| **GET** | `/otp` | `OTPController@index` | Menampilkan form verifikasi OTP SMS |
| **POST** | `/otp/send` | `OTPController@send` | Mengenerate OTP (Simulasi SMS) |
| **POST** | `/otp/verify` | `OTPController@verify` | Memverifikasi input kode OTP |
| **GET** | `/services` | `ServiceController@index` | Menampilkan katalog layanan laundry |
| **GET** | `/cart` | `CartController@index` | Menampilkan isi keranjang |
| **POST** | `/cart/add` | `CartController@add` | Menambah layanan ke keranjang |
| **POST** | `/cart/update` | `CartController@update` | Memperbarui jumlah (Qty) layanan |
| **POST** | `/cart/remove` | `CartController@remove` | Menghapus layanan dari keranjang |
| **GET** | `/checkout` | `CheckoutController@index` | Form checkout (dengan Leaflet Peta Jarak) |
| **POST** | `/checkout/process` | `CheckoutController@process` | Memproses order (Validasi < 3KM), Simpan DB |
| **GET** | `/checkout/success/{id}`| `CheckoutController@success` | Menampilkan halaman invoice/sukses pemesanan|
| **GET** | `/dashboard` | `DashboardController@index` | Dashboard User (Statistik Ringkasan) |
| **GET** | `/dashboard/orders` | `DashboardController@orders` | Tabel riwayat pesanan (History) |
| **GET** | `/dashboard/orders/{id}`| `DashboardController@orderDetail`| Detail Tracking, Stepper Status, & Rincian Harga |
| **GET** | `/dashboard/profile` | `DashboardController@profile` | Form edit profil pengguna |
| **POST** | `/dashboard/profile` | `DashboardController@updateProfile`| Update informasi dan password pengguna |
