# InfoMotive - Platform Otomotif Digital Terpadu

InfoMotive adalah aplikasi web modern yang dirancang untuk menghadirkan transparansi harga suku cadang (*sparepart*) otomotif, menyediakan edukasi mendalam terkait perawatan kendaraan, dan menyajikan direktori bengkel terpercaya di seluruh Indonesia. Dibangun dengan fokus pada pengalaman pengguna (UX) yang mulus, struktur database yang tangguh, serta asisten percakapan cerdas berbasis kecerdasan buatan (AI).

## 📌 Tujuan Project
Project ini berfungsi sebagai portofolio pengembangan web *full-stack*, menampilkan pola arsitektur yang bersih (*clean procedural architecture*), integrasi database yang matang, konsumsi RESTful API eksternal, serta teknik *prompt engineering* mutakhir menggunakan Generative AI (*Retrieval-Augmented Generation* / RAG).

## 🚀 Fitur Unggulan
- **Katalog Pintar & Transparansi Harga**: Pencarian suku cadang interaktif dengan jendela informasi (*modal*) langsung, pelacakan jumlah tayang (*view tracking*) otomatis, serta perbandingan rentang harga pasar (terendah hingga tertinggi).
- **Asisten Percakapan AI (BotMotif)**: Integrasi dengan Google Gemini AI menggunakan deteksi niat (*intent detection*) RAG tingkat lanjut dan logika cadangan lokal (*local fallback engine*). Bot mampu menjawab pertanyaan otomotif secara cerdas dan menolak topik di luar konteks dengan santun.
- **Direktori Bengkel Interaktif**: Integrasi peta visual (berbasis Leaflet & OpenStreetMap) yang memungkinkan pengguna menemukan lokasi bengkel mitra terpercaya terdekat dan melakukan navigasi langsung via Google Maps.
- **Pusat Edukasi & Pengetahuan**: Kurasi artikel tips otomotif, panduan perawatan berkala, dan keselamatan berkendara yang dilengkapi dengan fitur penyaringan kategori.
- **Dashboard Admin Menggunakan Proteksi Sesi**: Sistem manajemen konten (CMS) bagi administrator untuk mengelola produk, memantau analitik tayangan, dan mengawasi metrik sistem secara aman.

## 🛠️ Teknologi yang Digunakan
- **Frontend**: HTML5, Vanilla CSS (Design System kustom dengan estetika *Glassmorphism*), Vanilla JavaScript, FontAwesome 6, Leaflet.js.
- **Backend**: PHP 8.x (Pendekatan prosedural & *light-MVC* native), cURL untuk permintaan API eksternal.
- **Database**: MySQL / MariaDB dengan teknik *Prepared Statements* PDO (PHP Data Objects) guna mencegah ancaman *SQL Injection*.
- **Integrasi AI**: Google GenAI API (Gemini 1.5 Flash / 2.5 Flash) yang diperkuat dengan mekanisme rantai fallback multi-model untuk menjaga ketersediaan layanan.

## 📁 Struktur Repository
```text
InfoMotive/
├── admin/                  # Dashboard CMS admin terlindungi
├── api/                    # RESTful endpoint & penanganan percakapan AI
├── assets/                 # CSS kustom, file JavaScript, dan media statis
├── auth/                   # Modul otentikasi (Login, manajemen sesi)
├── config/                 # Konfigurasi lingkungan & koneksi database PDO
├── database/               # Skrip utilitas migrasi dan pengisian data (Seeder)
├── includes/               # Komponen UI modular (Widget chatbot, Modals)
└── index.php               # Landing page premium utama
```

## ⚙️ Panduan Instalasi & Konfigurasi Lokal

### 1. Persyaratan Sistem
- PHP 8.0 atau lebih tinggi
- MySQL / MariaDB (Rekomendasi: paket XAMPP / MAMP / LAMP)
- Git

### 2. Kloning Repository
```bash
git clone https://github.com/AnggiAdnanFauzi/InfoMotive.git
cd InfoMotive
```

### 3. Konfigurasi Lingkungan (*Environment*)
Salin file contoh konfigurasi ke file `.env` lokal Anda:
```bash
cp config/.env.example config/.env
```
Buka file `config/.env` dan masukkan kredensial database lokal serta API Key Gemini Anda:
```ini
DB_HOST=localhost
DB_NAME=bengkel_db
DB_USER=root
DB_PASS=
AI_PROVIDER=gemini
AI_API_KEY=masukkan_api_key_gemini_asli_anda_disini
```

### 4. Pengaturan Database
Buat database MySQL baru dengan nama `bengkel_db`. Aplikasi ini dilengkapi dengan mekanisme migrasi dan seeder otomatis. Ketika Anda mengakses aplikasi untuk pertama kalinya, sistem di `config/database.php` akan secara otomatis membentuk struktur tabel dan mengisi data awal.

### 5. Menjalankan Aplikasi
Anda dapat menggunakan server pengembangan bawaan PHP:
```bash
php -S localhost:8000
```
Buka aplikasi melalui browser Anda pada tautan `http://localhost:8000`.

## 📸 Cuplikan Antarmuka Aplikasi
- **Landing Page & Bagian Hero**: `assets/images/screenshots/landing.png`
- **Jendela Modal Katalog Pintar**: `assets/images/screenshots/catalog.png`
- **Widget AI Chatbot BotMotif**: `assets/images/screenshots/chatbot.png`
- **Peta Lokasi Bengkel**: `assets/images/screenshots/map.png`

## 🛣️ Peta Jalan Pengembangan (*Roadmap*)
- [x] Migrasi antarmuka lama ke sistem desain premium *Glassmorphism*.
- [x] Integrasi rantai fallback multi-model untuk AI Google Gemini.
- [ ] Restrukturisasi kode prosedural ke dalam arsitektur berorientasi objek (OOP) berbasis standar PSR-4.
- [ ] Implementasi pengujian otomatis (*Unit Testing*) menggunakan PHPUnit.

## 📄 Lisensi
Project ini berada di bawah Lisensi MIT - silakan periksa file LICENSE untuk informasi lebih lanjut.
