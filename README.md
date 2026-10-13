# RetroCam Inventory Management System - Laravel Edition (Mini Project 4)

Implementasi dan migrasi penuh dari **Mini Project 3 (Native PHP)** menuju **Laravel Fundamental (Pertemuan 5)**.

---

## 🚀 Panduan Cara Menjalankan

### 1. Masuk ke Folder Project:
```bash
cd d:\punya-safira\mini-project-4\inventory-laravel
```

### 2. Konfigurasi Environment (`.env`):
File `.env` sudah dikonfigurasikan secara otomatis menghubungkan database MySQL:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrasi Database & Seeder:
Jalankan migrasi fresh beserta seeder data demo:
```bash
php artisan migrate:fresh --seed
```

### 4. Jalankan Server Development:
```bash
php artisan serve
```
Buka browser pada URL:
```
http://127.0.0.1:8000
```

---

## 🔑 Akun Demo (Bawaan DatabaseSeeder)

| Role | Username | Password | Deskripsi Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin` | `password123` | Akses penuh (Katalog, Tambah/Edit/Hapus Produk, Kategori, Supplier, Mutasi Stok, Dashboard) |
| **Staff** | `staff` | `password123` | Terbatas (Katalog Produk & Mencatat Mutasi Stok IN/OUT) |

---

## 📐 Arsitektur MVC & Alur Data (Slide 10, 47)

Sesuai materi Pertemuan 5, alur aplikasi bekerja melalui siklus lengkap:
$$\text{Request} \longrightarrow \text{Route} \longrightarrow \text{Controller} \longrightarrow \text{Model / Eloquent} \longrightarrow \text{Database} \longrightarrow \text{Response / Blade View}$$

1. **Routing (`routes/web.php`):**
   - Mengelompokkan route dengan Middleware `auth`, `guest`, dan `role:admin`.
   - Menggunakan Named Route dan Resource Controller (`categories`, `suppliers`).
2. **Controller (`app/Http/Controllers/`):**
   - `AuthController`: Manajemen login, logout, dan `session()->regenerate()`.
   - `DashboardController`: Ringkasan agregat nilai inventaris, widget stok rendah, dan tren masuk/keluar.
   - `ProductController`: Resource controller lengkap untuk listing, create, edit, update, destroy, serta proteksi penghapusan produk berhistori.
   - `CategoryController`: Resource controller master data kategori produk.
   - `SupplierController`: Resource controller master data supplier/vendor rekanan.
   - `StockMovementController`: Menangani mutasi keluar-masuk stok dengan database transaction (`DB::transaction`) dan pencegahan stok minus (`lockForUpdate`).
3. **Model & Eloquent Relationship (`app/Models/`):**
   - **One-to-One:** `User` $\leftrightarrow$ `UserProfile` (`hasOne` / `belongsTo`).
   - **One-to-Many:** `Category` $\rightarrow$ `Product` (`hasMany` / `belongsTo`).
   - **Many-to-Many:** `Product` $\leftrightarrow$ `Supplier` (`belongsToMany` via tabel perantara `product_suppliers`).
   - **Histori:** `Product` $\rightarrow$ `StockMovement` & `User` $\rightarrow$ `StockMovement`.
4. **Blade Views (`resources/views/`):**
   - Layout modular: `resources/views/layouts/app.blade.php`.
   - Reusable Component: `resources/views/components/alert.blade.php`.
   - Directive Blade: `@extends`, `@section`, `@forelse`, `@if`, `@error`, `@csrf`, `@method`.
   - Styling UI Dark Slate Modern yang serasi dengan Mini Project 3 (`public/css/style.css`).

---

## 🧪 Verifikasi & Status Pengujian

- **Artisan Route List:** 32 route terdaftar rapi tanpa konflik.
- **Blade View Compilation:** Terverifikasi 100% valid melalui `php artisan view:cache`.
- **Automated Tests:** Berhasil lulus (`PASS Tests\Feature\ExampleTest` & `PASS Tests\Unit\ExampleTest`).
