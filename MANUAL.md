# 📘 SigmaKost — Manual Penggunaan Aplikasi

## Daftar Isi

- [Pendahuluan](#pendahuluan)
- [Instalasi & Setup](#instalasi--setup)
- [Akun Demo](#akun-demo)
- [Panduan Pengguna (User)](#panduan-pengguna-user)
  - [1. Halaman Utama & Login](#1-halaman-utama--login)
  - [2. Registrasi Akun Baru](#2-registrasi-akun-baru)
  - [3. Menjelajahi Kost](#3-menjelajahi-kost)
  - [4. Detail Kost](#4-detail-kost)
  - [5. Mengajukan Pemesanan (Booking)](#5-mengajukan-pemesanan-booking)
  - [6. Dashboard Pengguna](#6-dashboard-pengguna)
  - [7. Riwayat Pemesanan (My Bookings)](#7-riwayat-pemesanan-my-bookings)
  - [8. Pembayaran (My Payments)](#8-pembayaran-my-payments)
  - [9. Memberikan Ulasan (Review)](#9-memberikan-ulasan-review)
- [Panduan Admin](#panduan-admin)
  - [1. Login Admin](#1-login-admin)
  - [2. Dashboard Admin](#2-dashboard-admin)
  - [3. Manajemen Kategori](#3-manajemen-kategori)
  - [4. Manajemen Kost](#4-manajemen-kost)
  - [5. Manajemen Permintaan Sewa](#5-manajemen-permintaan-sewa)
  - [6. Manajemen Pembayaran](#6-manajemen-pembayaran)
- [Alur Lengkap (End-to-End Flow)](#alur-lengkap-end-to-end-flow)
- [Keamanan & Hak Akses](#keamanan--hak-akses)

---

## Pendahuluan

**SigmaKost** adalah aplikasi web manajemen kost (boarding house) yang dibangun dengan Laravel. Aplikasi ini memiliki dua sisi utama:

- **User (Pengguna)** — Menjelajahi kost, melakukan pemesanan, membayar sewa, dan memberikan ulasan.
- **Admin** — Mengelola data kost, kategori, permintaan sewa, dan verifikasi pembayaran.

### Teknologi yang Digunakan
| Komponen | Teknologi |
|----------|-----------|
| Backend | Laravel 12.x (PHP 8.3) |
| Database | PostgreSQL |
| Frontend (User) | Tailwind CSS + Vite |
| Frontend (Admin) | Bootstrap 5 + Font Awesome |
| Autentikasi | Laravel built-in session auth |

---

## Instalasi & Setup

### Prasyarat
- PHP 8.2+
- Composer
- Node.js 18+ & npm
- PostgreSQL

### Langkah Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/jirbthagoras/sigmakost.git
cd sigmakost

# 2. Install dependensi PHP
composer install

# 3. Install dependensi JS
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi database di .env
#    Sesuaikan DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 7. Jalankan migrasi + seeder
php artisan migrate:fresh --seed

# 8. Buat symlink storage
php artisan storage:link

# 9. Jalankan development server (2 terminal)
npm run dev          # Terminal 1: Vite dev server
php artisan serve    # Terminal 2: Laravel server
```

Aplikasi akan berjalan di **http://localhost:8000**.

---

## Akun Demo

Setelah menjalankan seeder, akun-akun berikut siap digunakan:

| Peran | Email | Password |
|-------|-------|----------|
| **Admin** | `admin@sigmakost.com` | `password` |
| User | `budi@example.com` | `password` |
| User | `siti@example.com` | `password` |
| User | `andi@example.com` | `password` |
| User | `dewi@example.com` | `password` |
| User | `rudi@example.com` | `password` |
| User | `putri@example.com` | `password` |

> **Catatan:** Semua password akun demo adalah `password`.

---

## Panduan Pengguna (User)

### 1. Halaman Utama & Login

**URL:** `/` (http://localhost:8000)

Halaman utama menampilkan dua bagian:
- **Kolom kiri:** Hero text dengan tagline "Temukan Kost Impian Anda" beserta keunggulan platform (Properti Terverifikasi, Booking Mudah, Support 24/7) dan tombol CTA untuk menjelajahi kost.
- **Kolom kanan:** Form login yang terintegrasi langsung di halaman utama.

#### Cara Login:
1. Buka halaman utama (`/`).
2. Pada form login di sebelah kanan, masukkan **email** dan **password**.
3. Centang "Ingat saya" (opsional) untuk menyimpan sesi.
4. Klik tombol **Sign In**.
5. Jika berhasil:
   - User biasa → diarahkan ke **Dashboard** (`/dashboard`).
   - Admin → diarahkan ke **Admin Panel** (`/admin`).
6. Jika gagal, akan muncul pesan error yang spesifik:
   - *"Email tidak terdaftar dalam sistem"* — jika email tidak ditemukan.
   - *"Password yang Anda masukkan salah"* — jika password tidak cocok.

### 2. Registrasi Akun Baru

**URL:** `/register`

1. Klik link **Sign Up** di navigasi atau di bawah form login.
2. Isi form registrasi:
   - **Name** — nama lengkap.
   - **Email** — alamat email (harus unik).
   - **Phone** — nomor telepon.
   - **Password** — minimal 8 karakter.
   - **Confirm Password** — ulangi password.
3. Klik **Create Account**.
4. Setelah berhasil, Anda akan otomatis login dan diarahkan ke Dashboard.

> **Catatan:** Akun baru selalu memiliki role `user`. Hanya admin yang dapat membuat akun admin lain melalui database.

### 3. Menjelajahi Kost

**URL:** `/kost`

Halaman ini menampilkan daftar seluruh kost yang tersedia.

#### Fitur:
- **Daftar kost** ditampilkan dalam bentuk kartu (card) dengan:
  - Gambar utama kost
  - Nama kost
  - Alamat
  - Harga per bulan (format Rupiah)
  - Jumlah kamar tersedia
  - Kategori (badge berwarna)
- **Filter & Pencarian:**
  - Cari berdasarkan nama atau alamat kost.
  - Filter berdasarkan kategori (Putra, Putri, Campur, Studio Room, Sharing Room).
  - Urutkan berdasarkan harga (termurah/termahal).
- Klik pada kartu kost untuk melihat detail.

### 4. Detail Kost

**URL:** `/kost/{id}`

Halaman detail kost menampilkan informasi lengkap:

#### Bagian Kiri (2/3 lebar):
- **Galeri Gambar** — Gambar utama besar di atas, thumbnail navigasi di bawah. Klik thumbnail untuk mengganti gambar utama.
- **Deskripsi** — Penjelasan lengkap tentang kost.
- **Fasilitas** — Daftar fasilitas yang tersedia (WiFi, AC, Kamar Mandi Dalam, dll.).
- **Peraturan** — Aturan-aturan yang berlaku di kost.
- **Ulasan Penghuni** — Review dari penghuni yang pernah atau sedang menyewa, termasuk rating bintang (1-5) dan komentar.
- **Lokasi** — Peta lokasi kost (jika koordinat tersedia).

#### Bagian Kanan (1/3 lebar, sidebar):
- **Kartu Harga** — Menampilkan harga per bulan dan status ketersediaan kamar.
- **Informasi Kontak** — Nomor telepon pengelola kost.
- **Form Booking** (jika user sudah login):
  - Pilih **Tanggal Mulai**.
  - Pilih **Durasi Sewa** (dalam bulan).
  - Lihat **Total Harga** yang otomatis dihitung.
  - Klik **Ajukan Sewa** untuk mengirim permintaan.

### 5. Mengajukan Pemesanan (Booking)

Proses pemesanan hanya dapat dilakukan oleh **pengguna yang sudah login**:

1. Buka halaman detail kost (`/kost/{id}`).
2. Pada sidebar kanan, isi form booking:
   - **Tanggal Mulai** — tanggal mulai sewa.
   - **Durasi** — berapa bulan ingin menyewa.
   - **Catatan** (opsional) — pesan tambahan untuk admin.
3. Klik tombol **Ajukan Sewa**.
4. Pemesanan akan masuk dengan status **Pending** (menunggu persetujuan admin).
5. Anda dapat melihat status pemesanan di halaman **Riwayat Pemesanan**.

> **Penting:** Pemesanan tidak otomatis disetujui. Admin harus memverifikasi dan menyetujui secara manual.

### 6. Dashboard Pengguna

**URL:** `/dashboard`

Dashboard menampilkan ringkasan informasi akun pengguna:
- Informasi profil (nama, email).
- Quick link ke fitur-fitur utama.
- Navigasi atas dengan menu: Dashboard, Lihat Pemesanan, Pembayaran.

### 7. Riwayat Pemesanan (My Bookings)

**URL:** `/my-bookings`

Halaman ini menampilkan semua pemesanan yang pernah diajukan:

- Setiap item menampilkan:
  - Nama kost & gambar thumbnail.
  - Tanggal mulai dan durasi sewa.
  - Total harga.
  - Status pemesanan dengan badge berwarna:
    - 🟡 **Pending** — Menunggu persetujuan admin.
    - 🟢 **Approved** — Disetujui, siap bayar.
    - 🔴 **Rejected** — Ditolak (dengan alasan penolakan jika ada).

#### Ulasan (Review):
- Untuk pemesanan berstatus **Approved**, Anda bisa memberikan ulasan:
  - Jika belum ada ulasan → muncul link **"+ Berikan Ulasan"**.
  - Klik untuk membuka form ulasan (rating bintang 1-5 + komentar).
  - Setelah mengirim → ulasan ditampilkan di bawah item pemesanan.

### 8. Pembayaran (My Payments)

**URL:** `/my-payments`

Halaman ini menampilkan semua tagihan pembayaran bulanan yang dikelompokkan per pemesanan:

#### Tampilan:
- **Header:** Nama kost, periode sewa, total harga.
- **Daftar tagihan** per bulan dengan:
  - Periode pembayaran (contoh: "April 2026").
  - Tanggal jatuh tempo.
  - Jumlah tagihan.
  - Status:
    - ⚪ **Belum Bayar** — Belum ada pembayaran.
    - 🟡 **Menunggu Verifikasi** — Bukti sudah diupload, menunggu admin.
    - 🟢 **Terverifikasi** — Pembayaran dikonfirmasi admin.
    - 🔴 **Terlambat** — Melewati tanggal jatuh tempo.

#### Cara Melakukan Pembayaran:
1. Pada tagihan berstatus "Belum Bayar", klik tombol **Upload Bukti**.
2. Modal pembayaran akan terbuka dengan 3 langkah:
   - **Pilih Metode Pembayaran:**
     - Transfer Bank
     - E-Wallet
     - Cash
   - **Upload Bukti Pembayaran:**
     - Klik area upload atau seret file ke drop zone.
     - Format yang diterima: **JPG, PNG, atau PDF**.
     - Ukuran maksimal: **5 MB**.
     - Setelah file dipilih, nama file akan ditampilkan dengan opsi hapus.
   - **Konfirmasi:**
     - Klik **"Kirim Bukti Pembayaran"**.
     - Popup konfirmasi akan muncul menampilkan detail periode dan jumlah.
     - Klik **"Ya, Kirim"** untuk mengirim.
3. Status berubah menjadi **"Menunggu Verifikasi"**.
4. Informasi upload ditampilkan (metode, tanggal bayar).
5. Tunggu admin memverifikasi pembayaran Anda.

### 9. Memberikan Ulasan (Review)

Ulasan hanya dapat diberikan untuk pemesanan yang berstatus **Approved**:

1. Buka halaman **Riwayat Pemesanan** (`/my-bookings`).
2. Pada pemesanan yang sudah disetujui, klik **"+ Berikan Ulasan"**.
3. Isi form:
   - **Rating** — klik bintang (1-5, wajib).
   - **Komentar** — tulis pengalaman Anda (wajib).
4. Klik **Kirim Ulasan**.
5. Ulasan akan muncul di:
   - Halaman pemesanan Anda.
   - Halaman detail kost (dapat dilihat oleh semua pengunjung).

---

## Panduan Admin

### 1. Login Admin

1. Buka halaman utama (`/`).
2. Login dengan akun admin: `admin@sigmakost.com` / `password`.
3. Anda akan otomatis diarahkan ke **Admin Panel** (`/admin`).

> **Keamanan:** Halaman admin dilindungi middleware. User biasa yang mencoba mengakses `/admin` akan diarahkan kembali ke dashboard user dengan pesan error.

### 2. Dashboard Admin

**URL:** `/admin`

Dashboard menampilkan ringkasan statistik dan aksi cepat:

#### Kartu Statistik (baris atas):
| Kartu | Keterangan | Warna Border |
|-------|------------|-------------|
| Total Kost | Jumlah seluruh kost yang terdaftar | Biru |
| Kost Aktif | Jumlah kost berstatus active | Hijau |
| Total Kamar | Akumulasi `room_count` semua kost | Cyan |
| Kamar Tersedia | Akumulasi `available_rooms` semua kost | Kuning |

#### Statistik Lainnya:
- **Kategori** — jumlah kategori yang terdaftar.
- **Pengguna** — jumlah total user terdaftar.

#### Kost Terbaru:
- Daftar kost yang baru ditambahkan dengan gambar thumbnail, nama, dan harga.
- Tombol **"Lihat Semua"** untuk ke halaman manajemen kost.

#### Aksi Cepat:
4 tombol shortcut:
- **+ Tambah Kost** → form tambah kost baru.
- **+ Tambah Kategori** → form tambah kategori baru.
- **Kelola Kost** → halaman daftar kost.
- **Kelola Kategori** → halaman daftar kategori.

### 3. Manajemen Kategori

**URL:** `/admin/categories`

#### Melihat Daftar Kategori:
Tabel dengan kolom: #, Nama Kategori, Slug, Deskripsi, Jumlah Kost, Aksi.

#### Menambah Kategori Baru:
1. Klik tombol **"+ Tambah Kategori"** di header halaman.
2. Isi form:
   - **Nama Kategori** — contoh: "Kost Putra" (wajib, unik).
   - **Slug** — otomatis dihasilkan dari nama, atau isi manual.
   - **Deskripsi** — penjelasan kategori (opsional).
3. Klik **Simpan**.

#### Mengedit Kategori:
1. Klik ikon ✏️ (Edit) pada baris kategori yang ingin diubah.
2. Ubah data sesuai kebutuhan.
3. Klik **Update**.

#### Menghapus Kategori:
1. Klik ikon 🗑️ (Hapus) pada baris kategori.
2. Modal konfirmasi akan muncul: *"Apakah Anda yakin ingin menghapus kategori ini?"*
3. Klik **Hapus** untuk mengonfirmasi.

> **Peringatan:** Menghapus kategori akan melepas hubungannya dengan semua kost yang terkait.

### 4. Manajemen Kost

**URL:** `/admin/kosts`

#### Melihat Daftar Kost:
Tabel dengan kolom:
| Kolom | Keterangan |
|-------|-----------|
| # | Nomor urut |
| Gambar | Thumbnail gambar utama |
| Nama Kost | Nama + alamat + badge kategori |
| Harga/Bulan | Harga sewa per bulan (format Rupiah) |
| Kamar | Total jumlah kamar |
| Tersedia | Jumlah kamar yang masih tersedia |
| Status | Active (hijau) / Inactive (abu-abu) |
| Aksi | Tombol Lihat, Edit, Hapus |

#### Menambah Kost Baru:
1. Klik **"+ Tambah Kost"**.
2. Isi form lengkap:
   - **Nama Kost** (wajib)
   - **Deskripsi** (wajib)
   - **Alamat** (wajib)
   - **Nomor Kontak** (wajib)
   - **Harga per Bulan** (wajib, angka)
   - **Jumlah Kamar** (wajib, angka)
   - **Kamar Tersedia** (wajib, angka ≤ jumlah kamar)
   - **Latitude & Longitude** (opsional, untuk peta)
   - **Fasilitas** — pilih atau tambah dari daftar fasilitas.
   - **Peraturan** — tambahkan aturan kost.
   - **Kategori** — pilih satu atau lebih kategori.
   - **Gambar** — upload satu atau lebih gambar (format: JPG, PNG, maks 2MB per file).
   - **Status** — Active atau Inactive.
3. Klik **Simpan**.

#### Mengedit Kost:
1. Klik ikon ✏️ pada baris kost.
2. Edit informasi sesuai kebutuhan.
3. Untuk gambar:
   - Upload gambar baru.
   - Hapus gambar yang tidak diperlukan (klik ❌).
   - Set gambar utama (klik ⭐) — gambar ini akan tampil sebagai thumbnail.
4. Klik **Update**.

#### Melihat Detail Kost:
Klik ikon 👁️ untuk melihat semua informasi kost termasuk gambar, fasilitas, ulasan penghuni, dan statistik pemesanan.

#### Mengubah Status Kost:
- Kost berstatus **Active** akan ditampilkan di halaman publik.
- Kost berstatus **Inactive** tidak terlihat oleh user.

#### Menghapus Kost:
1. Klik ikon 🗑️.
2. Konfirmasi di modal: *"Semua gambar dan data terkait akan ikut terhapus."*
3. Klik **Hapus**.

> **Peringatan:** Menghapus kost akan menghapus seluruh gambar, pemesanan, pembayaran, dan ulasan terkait (cascade delete).

### 5. Manajemen Permintaan Sewa

**URL:** `/admin/requests`

#### Melihat Permintaan:
Tabel menampilkan semua permintaan sewa dengan kolom: Pemohon (nama + email), Nama Kost, Tanggal Mulai / Durasi, Total Harga, Status, Aksi.

#### Status Permintaan:
| Status | Badge | Keterangan |
|--------|-------|-----------|
| Pending | 🟡 Kuning | Menunggu keputusan admin |
| Approved | 🟢 Hijau | Disetujui, pembayaran akan dibuat |
| Rejected | 🔴 Merah | Ditolak |

#### Menyetujui Permintaan:
1. Pada permintaan berstatus **Pending**, klik tombol **"✓ Setujui"**.
2. Popup konfirmasi akan muncul: *"Setujui permintaan sewa dari [Nama] untuk [Kost]?"*
3. Klik **"Setujui"** untuk mengonfirmasi.
4. Setelah disetujui:
   - Status berubah menjadi **Approved**.
   - Sistem otomatis membuat tagihan pembayaran bulanan sesuai durasi sewa.

#### Menolak Permintaan:
1. Klik tombol **"✗ Tolak"**.
2. Modal akan muncul meminta **Alasan Penolakan** (wajib diisi).
3. Tulis alasan penolakan (contoh: *"Kamar yang diminta tidak tersedia"*).
4. Klik **Tolak**.
5. User akan melihat status **Rejected** beserta alasan penolakan.

### 6. Manajemen Pembayaran

**URL:** `/admin/payments`

#### Melihat Data Pembayaran:
Tabel diurutkan berdasarkan prioritas: pembayaran yang perlu diverifikasi ditampilkan pertama.

Kolom tabel:
| Kolom | Keterangan |
|-------|-----------|
| Penyewa | Nama + email user |
| Kost | Nama kost yang disewa |
| Periode | Bulan & tahun tagihan |
| Jatuh Tempo | Tanggal batas pembayaran |
| Jumlah | Nominal tagihan |
| Status | Terverifikasi / Dibayar / Belum Bayar / Terlambat |
| Bukti | Link untuk melihat/download bukti pembayaran |
| Aksi | Tombol verifikasi (jika status "Dibayar") |

#### Status Pembayaran:
| Status | Badge | Keterangan |
|--------|-------|-----------|
| Belum Bayar | ⚫ Abu-abu | User belum upload bukti |
| Dibayar | 🟡 Kuning | Bukti sudah diupload, menunggu verifikasi |
| Terverifikasi | 🟢 Hijau | Dikonfirmasi oleh admin |
| Terlambat | 🔴 Merah | Melewati jatuh tempo, belum dibayar |

#### Melihat Bukti Pembayaran:
- Jika bukti berupa **gambar** (JPG/PNG) → klik **"📷 Lihat"** untuk membuka di tab baru.
- Jika bukti berupa **PDF** → klik **"📄 PDF"** untuk download.
- Jika belum ada bukti → ditampilkan tanda **"-"**.

#### Memverifikasi Pembayaran:
1. Pada pembayaran berstatus **"Dibayar"**, periksa bukti pembayaran terlebih dahulu.
2. Klik tombol **"✓ Verifikasi"**.
3. Popup konfirmasi: *"Verifikasi pembayaran Rp X dari [Nama]?"*
4. Klik **"Ya, Verifikasi"**.
5. Status berubah menjadi **Terverifikasi** dengan catatan nama admin dan waktu verifikasi.

---

## Alur Lengkap (End-to-End Flow)

Berikut adalah alur penggunaan aplikasi dari awal sampai akhir:

```
┌───────────────────────────────────────────────────────────────┐
│                        USER FLOW                              │
├───────────────────────────────────────────────────────────────┤
│                                                               │
│  1. Register/Login                                            │
│       ↓                                                       │
│  2. Browse Kost (/kost)                                       │
│       ↓                                                       │
│  3. Lihat Detail (/kost/{id})                                 │
│       ↓                                                       │
│  4. Ajukan Sewa (status: PENDING)                             │
│       ↓                                                       │
│  ─── Menunggu Admin ───────────────────────                   │
│       ↓                                                       │
│  5a. APPROVED → Tagihan dibuat otomatis                       │
│       ↓                                                       │
│  6. Upload Bukti Pembayaran (/my-payments)                    │
│       ↓                                                       │
│  ─── Menunggu Verifikasi Admin ───────────                    │
│       ↓                                                       │
│  7. Pembayaran TERVERIFIKASI                                  │
│       ↓                                                       │
│  8. Berikan Ulasan (/my-bookings)                             │
│                                                               │
│  5b. REJECTED → Lihat alasan di /my-bookings                  │
│                                                               │
└───────────────────────────────────────────────────────────────┘

┌───────────────────────────────────────────────────────────────┐
│                       ADMIN FLOW                              │
├───────────────────────────────────────────────────────────────┤
│                                                               │
│  1. Login (/admin)                                            │
│       ↓                                                       │
│  2. Setup Data Awal:                                          │
│     • Tambah Kategori (/admin/categories/create)              │
│     • Tambah Kost (/admin/kosts/create)                       │
│       ↓                                                       │
│  3. Kelola Permintaan Sewa (/admin/requests)                  │
│     • Setujui atau Tolak permintaan                           │
│       ↓                                                       │
│  4. Verifikasi Pembayaran (/admin/payments)                   │
│     • Periksa bukti → Verifikasi                              │
│                                                               │
└───────────────────────────────────────────────────────────────┘
```

---

## Keamanan & Hak Akses

### Proteksi Rute (Route Guards)

| Rute | Middleware | Perilaku |
|------|-----------|----------|
| `/` | — | Publik, dapat diakses semua orang |
| `/kost`, `/kost/{id}` | — | Publik, dapat diakses semua orang |
| `/login`, `/register` | `guest` | Hanya untuk pengguna belum login. Jika sudah login → redirect ke dashboard |
| `/logout` | `auth` | Hanya untuk pengguna yang sudah login |
| `/dashboard`, `/my-bookings`, `/my-payments` | `auth` | Harus login. Jika belum → redirect ke halaman utama |
| `/admin/*` | `auth` + `admin` | Harus login sebagai admin. User biasa → redirect ke dashboard dengan pesan error |

### Keamanan Pembayaran
- Upload bukti hanya diterima format **JPG, PNG, atau PDF** (maks **5 MB**).
- File disimpan di `storage/app/public/payment-proofs/` dan diakses via symlink.
- Setiap pembayaran diverifikasi manual oleh admin sebelum dianggap sah.
- Riwayat verifikasi mencatat **siapa** yang memverifikasi dan **kapan**.

### Validasi Data
- Semua form memiliki validasi server-side (Laravel validation).
- Email harus unik saat registrasi.
- Harga dan jumlah kamar harus berupa angka positif.
- Tanggal mulai sewa harus di masa depan.

---

> **Dibuat untuk SigmaKost** — Sistem Manajemen Kost Modern  
> Versi: 1.0 | Terakhir diperbarui: April 2026
