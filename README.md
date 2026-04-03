# TypeRush 🏎️💨

**TypeRush** adalah platform latihan mengetik cepat modern yang dibangun untuk memberikan pengalaman mengetik yang mulus, akurat, dan menantang. Tingkatkan kecepatan jarimu dan pantau progresmu dalam satu aplikasi yang elegan.

---

## ✨ Fitur Utama
- **Real-time Typing Engine**: Perhitungan WPM (Words Per Minute) dan akurasi secara langsung saat Anda mengetik. 🚀
- **Clean UI/UX**: Antarmuka minimalis namun premium menggunakan Tailwind CSS. 🎨
- **Authentication**: Sistem akun untuk menyimpan progres dan hasil latihan. 👤
- **Responsive Design**: Optimal untuk digunakan di berbagai ukuran layar. 📱💻
- **Inertia.js Power**: Pengalaman Single Page Application (SPA) dengan kekuatan backend Laravel. ⚡

---

## 🛠️ Tech Stack
- **Backend**: [Laravel 11](https://laravel.com/) 🐘
- **Frontend**: [Vue 3](https://vuejs.org/) + [Inertia.js](https://inertiajs.com/) 🧬
- **Styling**: [Tailwind CSS](https://tailwindcss.com/) 🎨
- **Database**: 
  - Lokal: SQLite 📂
  - Cloud Support: PostgreSQL (Supabase/Neon) ☁️
- **Build Tool**: [Vite](https://vitejs.dev/) ⚡

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan TypeRush di komputer Anda:

1.  **Clone Repository**
    ```bash
    git clone https://github.com/Havinoia/typerush.git
    cd typerush
    ```

2.  **Instal Dependensi**
    ```bash
    composer install
    npm install
    ```

3.  **Setup Environment**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Migrasi & Seed Database**
    ```bash
    # Memastikan database sqlite sudah ada
    touch database/database.sqlite
    
    # Jalankan migrasi dan isi data awal
    php artisan migrate:fresh --seed
    ```

5.  **Jalankan Aplikasi**
    Langkah ini membutuhkan dua terminal:
    - **Terminal 1 (PHP)**: `php artisan serve`
    - **Terminal 2 (Vite)**: `npm run dev`

---

## 👤 Akun Uji Coba (Test Credentials)

Gunakan akun ini untuk langsung mencoba fitur TypeRush tanpa perlu mendaftar:
- **Email**: `a@a`
- **Password**: `a`

---

## 🏁 Author
Dibuat dengan ❤️ oleh **Havinoia**.

---
*Selamat mencoba dan jadilah pengetik tercepat!* 🏁🏎️🌟🏆✨
