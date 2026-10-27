# Sistem Rekomendasi Pemilihan Mobil Berbasis Knowledge-Based Constraints

![PHP](https://img.shields.io/badge/PHP-8.5+-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-Framework-FF2D20?logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white)
![Recommendation System](https://img.shields.io/badge/Recommendation-System-6A5ACD)

## Tentang Proyek

Sistem Rekomendasi Pemilihan Mobil merupakan aplikasi berbasis web yang dikembangkan sebagai bagian dari skripsi Program Studi Informatika. Aplikasi ini membantu pengguna menemukan mobil yang sesuai dengan kebutuhan dan preferensi mereka menggunakan pendekatan _Knowledge-Based_.

Sistem menerapkan metode **Constraint-Based Filtering** untuk menyaring alternatif mobil dan **Similarity Scoring** untuk menghitung tingkat kesesuaian serta mengurutkan rekomendasi berdasarkan preferensi pengguna.

Kriteria pemilihan meliputi harga target, kapasitas mesin minimum, jenis bahan bakar, dan jumlah kursi minimum.

## Fitur Utama

- **Pemilihan Kriteria Mobil:** Menentukan preferensi berdasarkan harga, kapasitas mesin, jenis bahan bakar, dan jumlah kursi.
- **Constraint-Based Filtering:** Menyaring mobil yang memenuhi kriteria pengguna.
- **Similarity Scoring:** Menghitung skor kemiripan berdasarkan kesesuaian spesifikasi mobil dengan preferensi pengguna.
- **Peringkat Rekomendasi:** Mengurutkan alternatif mobil berdasarkan skor kemiripan.

## Metode Rekomendasi

### 1. Constraint-Based Filtering

Metode ini digunakan untuk menyaring data mobil berdasarkan batasan yang ditentukan pengguna. Mobil yang tidak memenuhi kriteria yang dipersyaratkan akan dikeluarkan dari kandidat rekomendasi.

### 2. Similarity Scoring

Metode ini digunakan untuk mengukur kesesuaian mobil dengan preferensi pengguna. Skor kemiripan yang dihasilkan digunakan untuk mengurutkan kandidat mobil yang memenuhi batasan.

## Teknologi yang Digunakan

- **Bahasa Pemrograman:** PHP
- **Framework:** Laravel
- **Basis Data:** MySQL
- **Metode Rekomendasi:** Constraint-Based Filtering dan Similarity Scoring

## Dataset

Dataset berisi informasi spesifikasi mobil yang digunakan dalam proses rekomendasi, meliputi:

- Merek mobil
- Nama atau model mobil
- Kapasitas mesin
- Harga
- Jenis bahan bakar
- Jumlah kursi

## Pengujian dan Evaluasi

Pengujian sistem dilakukan melalui dua tahap:

- **Alpha Testing:** Untuk memvalidasi fungsionalitas sistem.
- **Beta Testing:** Untuk mengevaluasi usability sistem dengan melibatkan 20 responden.

Hasil evaluasi beta testing memperoleh skor usability sebesar **89%**, yang dikategorikan sebagai sangat baik.

## Cara Menjalankan Proyek

### Prasyarat

Pastikan perangkat telah memiliki:

- PHP dengan versi yang sesuai dengan proyek
- Composer
- MySQL
- Git

### Langkah Instalasi

1. Kloning repositori:

    ```bash
    git clone https://github.com/cahyokml/RekomendasiMobil.git
    ```

2. Masuk ke direktori proyek:

    ```bash
    cd RekomendasiMobil
    ```

3. Instal dependensi:

    ```bash
    composer install
    ```

4. Salin file `.env.example` menjadi `.env`.

5. Buat database MySQL dan sesuaikan konfigurasi koneksi database pada file `.env`.

6. Buat application key:

    ```bash
    php artisan key:generate
    ```

7. Siapkan struktur database dan data mobil sesuai dengan konfigurasi proyek.

8. Jalankan aplikasi:

    ```bash
    php artisan serve
    ```

9. Buka aplikasi melalui browser:

    ```text
    http://127.0.0.1:8000
    ```

**Catatan:** Langkah instalasi perlu disesuaikan dengan file konfigurasi, migrasi, dan sumber data yang tersedia di repositori.

## Pengembang

Proyek skripsi Program Studi Informatika.

**Repositori GitHub:** [RekomendasiMobil](https://github.com/cahyokml/RekomendasiMobil)
