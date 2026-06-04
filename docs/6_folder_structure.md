# Struktur Folder Laravel

Sistem dikembangkan menggunakan arsitektur MVC (Model-View-Controller) bawaan framework Laravel 12. Berikut adalah struktur direktori utamanya:

```text
C:\SEMESTER 4\pemrograman web\UAS-LAUNDRY\
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php       # Autentikasi User
│   │   │   ├── CartController.php       # Manajemen Keranjang Session
│   │   │   ├── CheckoutController.php   # Validasi Jarak & Pembuatan Order
│   │   │   ├── DashboardController.php  # Dashboard & Riwayat
│   │   │   ├── LandingController.php    # Landing Page & Tracking
│   │   │   ├── OTPController.php        # Simulasi Verifikasi SMS
│   │   │   └── ServiceController.php    # Menampilkan Katalog Layanan
│   │   └── Middleware/
│   │       └── EnsurePhoneIsVerified.php # Middleware Cek Verifikasi HP
│   └── Models/
│       ├── DeliverySchedule.php
│       ├── Order.php
│       ├── OrderItem.php
│       ├── OrderStatus.php
│       ├── Payment.php
│       ├── PhoneVerification.php
│       ├── Service.php
│       └── User.php
├── bootstrap/
│   └── app.php (Registrasi Middleware verified.phone)
├── database/
│   ├── migrations/ (Struktur Tabel)
│   └── seeders/ (Data Dummy/Awal)
├── docs/ (Dokumentasi Lengkap Proyek)
├── resources/
│   ├── css/
│   │   └── app.css (Tailwind CSS v4 Configuration)
│   └── views/ (Blade Templates)
│       ├── auth/ (Login, Register, OTP)
│       ├── cart/ (Keranjang)
│       ├── checkout/ (Checkout & Peta, Invoice Sukses)
│       ├── dashboard/ (Overview, Orders, Detail, Profile)
│       ├── layouts/ (app.blade.php / Master Layout Soft Pink Theme)
│       ├── services/ (Katalog)
│       ├── landing.blade.php
│       └── track-result.blade.php
├── routes/
│   └── web.php (Deklarasi semua rute sistem)
└── .env (Konfigurasi Database)
```
