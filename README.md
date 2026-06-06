# PocketMon
Final Project Pemrograman Website - Sistem Manajemen Keuangan Pribadi Berbasis Web

PocketMon adalah aplikasi web manajemen keuangan pribadi yang membantu pengguna mencatat pemasukan, pengeluaran, target tabungan (brankas), dan memantau kondisi keuangan secara mudah dan terorganisir.

Website ini dikembangkan untuk membantu pengguna mengelola keuangan secara lebih teratur melalui pencatatan transaksi, pemantauan saldo wallet, serta visualisasi laporan keuangan yang informatif.

---

## Fitur Utama

### Dashboard
- Ringkasan total saldo dari semua wallet
- Total pemasukan dan pengeluaran
- Progress brankas tabungan aktif
- Grafik keuangan 6 bulan terakhir
- Transaksi terbaru

### Manajemen Wallet
- Tambah, edit, hapus wallet
- Tipe wallet: Cash, Bank Account, Credit Card, E-Wallet
- Transfer antar wallet
- Saldo otomatis berubah setiap ada transaksi

### Manajemen Pemasukan
- Tambah, edit, hapus pemasukan
- Kategori: Gaji, Bonus, Bisnis, Freelance, Hadiah, Lainnya
- Filter berdasarkan kategori dan tanggal
- Pencarian data

### Manajemen Pengeluaran
- Tambah, edit, hapus pengeluaran
- Kategori: Makanan, Transportasi, Pendidikan, Belanja, Hiburan, Kesehatan, Tagihan, Lainnya
- Filter berdasarkan kategori dan tanggal
- Pencarian data

### Brankas (Target Tabungan)
- Membuat target tabungan dengan nama brankas
- Progress bar pencapaian otomatis
- Prioritas: Tinggi, Sedang, Rendah
- Status: Belum Tercapai / Tercapai
- Deadline tabungan

### Riwayat Transaksi
- Menampilkan seluruh aktivitas keuangan
- Filter berdasarkan jenis transaksi dan tanggal
- Pencarian data transaksi
- Pagination

### Laporan Keuangan
- Ringkasan keuangan bulanan
- Grafik pemasukan dan pengeluaran 12 bulan terakhir
- Breakdown per kategori

### Autentikasi
- Register & Login
- Session authentication
- Edit profil & ganti password

---

## Desain

PocketMon menggunakan konsep desain modern, minimalis, dan soft aesthetic dengan kombinasi warna pastel:

| Warna | Kode |
|-------|------|
| Soft Pink | #F8D7DA |
| Soft Mint | #D1E7DD |
| Soft Lavender | #E2D9F3 |
| Light Background | #F8F9FA |

Font yang digunakan: **Poppins**

---

## Tech Stack

### Frontend
- Blade Templates
- Tailwind CSS
- JavaScript
- Chart.js

### Backend
- PHP 8.3
- Laravel 13

### Database
- MySQL

### Local Development
- Laragon

---

## Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/username/pocketmon.git
cd pocketmon
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database
```bash
Edit file `.env`:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pocketmon
DB_USERNAME=root
DB_PASSWORD=
```bash

### 5. Migrasi Database
```bash
php artisan migrate
```

### 6. Jalankan Aplikasi
```bash
npm run dev
php artisan serve
```

### 7. Buka Browser
localhostmimimi

---

## Struktur Database

- `users` — data pengguna
- `wallets` — data wallet/akun keuangan
- `wallet_transfers` — riwayat transfer antar wallet
- `incomes` — data pemasukan
- `expenses` — data pengeluaran
- `brankas` — data target tabungan
- `savings_goals` — data savings goals

---

## Tujuan Pengembangan

PocketMon dikembangkan sebagai solusi sederhana untuk membantu mahasiswa, fresh graduate, dan anak muda usia 18–30 tahun dalam mengelola keuangan pribadi secara lebih efektif, terstruktur, dan mudah dipantau.

---

## Developed By

**Gishub Team**
Software Engineering Project — Pemrograman Website
