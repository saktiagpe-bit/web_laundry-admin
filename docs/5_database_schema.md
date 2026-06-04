# Database Schema

Sistem BubbleWash dibangun menggunakan 8 tabel utama.

### 1. Tabel `users`
Tabel untuk menyimpan data autentikasi dan profil pengguna.
- `id` (PK, BigInt)
- `name` (String)
- `email` (String, Unique)
- `password` (String)
- `phone` (String, Nullable, Unique)
- `phone_verified_at` (Timestamp, Nullable)
- `gender` (String, Nullable)
- `address` (Text, Nullable)
- `role` (String, Default: 'user')
- `timestamps`

### 2. Tabel `phone_verifications`
Menyimpan kode OTP sementara untuk verifikasi.
- `id` (PK, BigInt)
- `phone` (String)
- `code` (String, 6 digit)
- `expires_at` (Timestamp)
- `verified_at` (Timestamp, Nullable)

### 3. Tabel `services`
Katalog master layanan laundry.
- `id` (PK, BigInt)
- `name` (String)
- `slug` (String, Unique)
- `description` (Text, Nullable)
- `price` (Integer)
- `estimate_hours` (Integer)

### 4. Tabel `orders`
Tabel transaksi utama (Header Pemesanan).
- `id` (PK, BigInt)
- `user_id` (FK, Nullable)
- `transaction_number` (String, Unique, misal: TRX-20240101-ABCDEF)
- `customer_name` (String)
- `customer_email`, `customer_phone`, `customer_gender`
- `pickup_type` (Enum/String: driver/self)
- `delivery_type` (Enum/String: driver/self)
- `distance_km` (Decimal, Maksimal 3.0 KM)
- `address` (Text)
- `latitude`, `longitude` (String)
- `status` (String, Default: Antre)
- `total_price` (Integer)

### 5. Tabel `order_items`
Tabel detail layanan yang dipesan (Detail Pemesanan).
- `id` (PK, BigInt)
- `order_id` (FK to orders)
- `service_id` (FK to services)
- `service_name` (String)
- `price`, `quantity`, `subtotal` (Integer)

### 6. Tabel `payments`
Tabel pencatatan metode pembayaran dan status.
- `id` (PK, BigInt)
- `order_id` (FK)
- `payment_method` (String: cash/qris)
- `payment_status` (String: unpaid/paid)
- `amount` (Integer)
- `paid_at` (Timestamp)

### 7. Tabel `order_statuses`
Histori pergerakan status pesanan (Tracking).
- `id` (PK, BigInt)
- `order_id` (FK)
- `status` (String)
- `description` (Text)

### 8. Tabel `delivery_schedules`
Jadwal penjemputan dan pengantaran.
- `id` (PK, BigInt)
- `order_id` (FK)
- `pickup_date` (Date)
- `pickup_time` (Time)
- `delivery_date` (Date, Nullable)
- `delivery_time` (Time, Nullable)
