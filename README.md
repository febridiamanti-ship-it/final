# 🏠 BaKos – Cari Kos Tanpa Ribet
**Platform cari kos di Manado & Sulawesi Utara**
*Laravel 11 + Tailwind CSS + Alpine.js + Leaflet.js*

---

## 🚀 Setup Lengkap

### 1. Buat Project Laravel 11
```bash
cd C:\xampp\htdocs
composer create-project laravel/laravel BaKost
cd BaKost
```

### 2. Copy Semua File dari Zip Ini
Extract zip → copy semua isi folder ke `C:\xampp\htdocs\BaKost\`
Pilih **Replace** jika ada konfirmasi overwrite.

### 3. Taruh Logo BaKos
Copy file logo (gambar yang sudah ada) ke:
```
C:\xampp\htdocs\BaKost\public\images\logo-bakos.png
```
Rename file logo menjadi `logo-bakos.png`

### 4. Setup Database
Buka phpMyAdmin → buat database `bakost`

Edit file `.env`:
```env
APP_NAME=BaKos
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bakost
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan Semua Command (berurutan)
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
npm install
npm run dev
```

### 6. Jalankan Server
Buka terminal baru:
```bash
php artisan serve
```
Buka browser: **http://localhost:8000**

---

## 📁 File yang Disertakan

```
BaKost/
├── app/Http/Controllers/KosController.php
├── app/Models/Kos.php
├── database/migrations/..._create_kos_table.php
├── database/seeders/KosSeeder.php
├── resources/css/app.css
├── resources/js/app.js
├── resources/js/bootstrap.js
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── components/kos-card.blade.php
│   ├── vendor/pagination/tailwind.blade.php
│   ├── home.blade.php
│   └── kos/
│       ├── index.blade.php
│       ├── show.blade.php
│       ├── create.blade.php
│       └── edit.blade.php
├── routes/web.php
├── tailwind.config.js
├── vite.config.js
└── package.json
```

---

## 📍 Data Dummy – Manado (10 kos)

6 kos di Kec. Bunaken, Kel. Pandu + 4 area Manado lainnya.

---

© 2025 BaKos – Cari Kos Tanpa Ribet 🌊
