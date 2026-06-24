# InfoMotive - Platform Otomotif Digital Terpadu

InfoMotive adalah aplikasi web otomotif yang dirancang untuk memberikan kemudahan akses informasi suku cadang (*sparepart*), artikel edukasi seputar perawatan kendaraan, serta direktori bengkel terdekat. 

Aplikasi ini juga dilengkapi dengan asisten pintar berbasis AI (Google Gemini) yang siap menjawab pertanyaan seputar dunia otomotif kapan saja.

## 📌 Fitur Utama
- **Katalog Suku Cadang**: Menyajikan informasi perbandingan harga pasar untuk berbagai suku cadang kendaraan.
- **Asisten AI BotMotif**: Menggunakan teknologi Google Gemini AI untuk membantu menjawab pertanyaan seputar otomotif, servis, dan produk InfoMotive.
- **Direktori Bengkel**: Peta interaktif berbasis OpenStreetMap/Leaflet untuk mempermudah pencarian bengkel mitra terpercaya.
- **Artikel Edukasi**: Kumpulan panduan, tips perawatan berkala, dan keselamatan berkendara.
- **Dashboard Admin**: Fitur pengelolaan (*CMS*) bagi pengelola sistem untuk mengelola data katalog, artikel, dan daftar bengkel.

## 🛠️ Teknologi & Arsitektur
Aplikasi ini dibangun menggunakan pendekatan PHP Native / Prosedural dengan mengutamakan kesederhanaan dan kinerja tinggi tanpa ketergantungan pada pustaka eksternal yang berat.
- **Frontend**: HTML5, Vanilla CSS (*Glassmorphism Design*), Vanilla JavaScript, FontAwesome, Leaflet.js.
- **Backend**: PHP 8.x, cURL.
- **Database**: MySQL / MariaDB dengan ekstensi PDO (*PHP Data Objects*).

## ⚙️ Panduan Instalasi Lokal

1. **Kloning Repository**
   ```bash
   git clone https://github.com/AnggiAdnanFauzi/InfoMotive.git
   cd InfoMotive
   ```

2. **Konfigurasi Variabel Lingkungan**
   Salin file `config/.env.example` menjadi `config/.env`:
   ```bash
   cp config/.env.example config/.env
   ```
   Buka `config/.env` dan sesuaikan pengaturan database serta masukkan API Key Google Gemini Anda:
   ```ini
   DB_HOST=localhost
   DB_NAME=bengkel_db
   DB_USER=root
   DB_PASS=
   AI_PROVIDER=gemini
   AI_API_KEY=masukkan_api_key_gemini_asli_anda_disini
   ```

3. **Inisialisasi Database**
   Buat database MySQL baru bernama `bengkel_db`. 
   Sistem di `config/database.php` telah dikonfigurasi untuk secara otomatis membentuk struktur tabel dan mengisi data awal (*seed data*) saat aplikasi pertama kali dibuka di browser.

4. **Jalankan Aplikasi**
   Gunakan server PHP bawaan atau jalankan melalui XAMPP/MAMP:
   ```bash
   php -S localhost:8000
   ```
   Buka browser dan kunjungi `http://localhost:8000`.

## 👥 Tim Pengembang
Project ini dikembangkan oleh tim yang berdedikasi tinggi untuk memajukan ekosistem layanan otomotif di Indonesia:
- **Anggi Adnan Fauzi**
- **Adam Atma Wiguna**
- **Arbi Fadhlurrahman**
- **M. Gilang Romadhon**
- **Taura Rahayudin**

---
*© InfoMotive - Terbuka di bawah Lisensi MIT.*
