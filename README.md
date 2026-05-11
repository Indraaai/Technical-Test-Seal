# Backend Leave Management API

RESTful API untuk sistem manajemen cuti karyawan. Project ini dibuat menggunakan Laravel 13 dengan authentication konvensional, OAuth login, role authorization, workflow pengajuan cuti, dan pembatasan kuota cuti tahunan.

## Tech Stack

- PHP 8.2+
- Laravel 13
- MySQL
- Laravel Sanctum
- Laravel Socialite
- Postman Documentation

## Fitur Utama

- Register dan login konvensional
- Login OAuth menggunakan Google
- Authentication menggunakan Bearer Token
- Role authorization:
    - Employee
    - Admin
- Employee dapat membuat dan melihat pengajuan cuti miliknya sendiri
- Admin dapat melihat semua pengajuan cuti
- Admin dapat approve atau reject pengajuan cuti
- Kuota cuti maksimal 12 hari per tahun
- Upload attachment sebagai bukti pendukung pengajuan cuti
- Workflow status:
    - pending
    - approved
    - rejected

---

## Installation Guide

### 1. Clone Repository

```bash
git clone https://github.com/Indraaai/Technical-Test-Seal
cd backend-leave-management
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Copy Environment File

```bash
cp .env.example .env
```

Untuk Windows:

```bash
copy .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Setup Database

Buat database baru di MySQL:

```sql
CREATE DATABASE backend_leave_management;
```

Lalu sesuaikan konfigurasi database di file `.env`:

```env
APP_NAME="Backend Leave Management"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=backend_leave_management
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Setup Sanctum API

Jika belum menjalankan install API, jalankan:

```bash
php artisan install:api
```

### 7. Setup OAuth Google

Install Socialite jika belum:

```bash
composer require laravel/socialite
```

Tambahkan konfigurasi berikut di `.env`:

```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/api/v1/auth/google/callback
```

Pastikan `config/services.php` memiliki konfigurasi berikut:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

Pada Google Cloud Console, tambahkan Authorized Redirect URI:

```txt
http://127.0.0.1:8000/api/v1/auth/google/callback
```

### 8. Run Migration

```bash
php artisan migrate
```

### 9. Run Admin Seeder

```bash
php artisan db:seed --class=AdminUserSeeder
```

Default admin account:

```txt
Email: admin@gmail.com
Password: password123
```

### 10. Create Storage Link

```bash
php artisan storage:link
```

Storage link digunakan agar file attachment yang disimpan di `storage/app/public` dapat diakses melalui URL public.

### 11. Clear Cache

```bash
php artisan optimize:clear
```

### 12. Run Development Server

```bash
php artisan serve
```

Base URL:

```txt
http://127.0.0.1:8000/api/v1
```

---

## Environment Configuration

Contoh konfigurasi `.env`:

```env
APP_NAME="Backend Leave Management"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=backend_leave_management
DB_USERNAME=root
DB_PASSWORD=

GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/api/v1/auth/google/callback
```

---

## System Architecture

Project ini menggunakan layered architecture sederhana agar kode lebih rapi, mudah dibaca, dan mudah dikembangkan.

Struktur utama:

```txt
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   ├── Requests/
│   └── Resources/
│
├── Models/
│
├── Repositories/
│
├── Services/
│
└── Support/
```

Alur request API:

```txt
Route
→ Controller
→ Form Request
→ Service
→ Repository
→ Model / Database
→ Resource
→ ApiResponse
```

## Database Overview

### Users Table

Digunakan untuk menyimpan data user employee dan admin.

Field penting:

```txt
id
name
email
password
role
provider
provider_id
created_at
updated_at
```

`role` digunakan untuk membedakan akses employee dan admin.

`provider` dan `provider_id` digunakan untuk OAuth login.

### Leave Requests Table

Digunakan untuk menyimpan data pengajuan cuti.

Field penting:

```txt
id
user_id
start_date
end_date
total_days
reason
attachment_path
status
reviewed_by
reviewed_at
rejection_reason
created_at
updated_at
```

Status pengajuan:

```txt
pending
approved
rejected
```

---

## Published Postman Documentation

```txt
https://documenter.getpostman.com/view/48218266/2sBXqNmyak
```

---

## Default Admin Account

```txt
Email: admin@gmail.com
Password: password123
```
