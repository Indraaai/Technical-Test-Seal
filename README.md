# Backend Leave Management API

RESTful API untuk sistem manajemen cuti karyawan. Project ini dibuat menggunakan Laravel 13 dengan authentication konvensional, OAuth login, role authorization, workflow pengajuan cuti, dan pembatasan kuota cuti tahunan.

## Tech Stack

- PHP 8.2+
- Laravel 13
- MySQL
- Laravel Sanctum
- Laravel Socialite
- Postman Documentation

## Main Features

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

# Installation Guide

## 1. Clone Repository

```bash
git clone https://github.com/username/backend-leave-management.git
cd backend-leave-management
```

## 2. Install Dependencies

- composer install
