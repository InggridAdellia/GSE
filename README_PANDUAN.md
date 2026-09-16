# Sistem Perizinan GSE Bandara (CodeIgniter 3 + PostgreSQL)

## 1. Yang sudah dibenahi/dilengkapi dari project awal kamu
- Class kembar `PermohonanMasuk` di `permohonan_keluar.php` (bikin fatal error) → dipisah jadi controller sendiri-sendiri.
- Nama file controller & model disesuaikan hurufnya (huruf awal kapital), karena CodeIgniter 3 mencari file berdasarkan nama class dan ini **case-sensitive di server Linux**. Kalau hurufnya tidak pas, di Windows/XAMPP biasanya masih "kebetulan jalan", tapi begitu di-upload ke hosting Linux akan 404.
- Model yang belum ada (`Gse_model`, `Permohonan_masuk_model`, `Permohonan_keluar_model`, `Approval_model`) dibuatkan lengkap dengan CRUD.
- View yang belum ada (`template/header`, `template/footer`, `gse/*`, `permohonan_masuk/*`, `permohonan_keluar/*`, `approval/index`) dibuatkan dengan tampilan Bootstrap 5 (lewat CDN, jadi tidak perlu install apa-apa lagi).
- Folder view dashboard yang salah ketik (`dasboard` → `dashboard`).
- Ditambahkan `MY_Controller` (di `application/core/`) supaya semua halaman yang butuh login tidak perlu menulis ulang pengecekan session di setiap controller.
- Dibuatkan skema database PostgreSQL lengkap (`database/gse_db.sql`) + data contoh.
- Approval sekarang langsung bekerja di atas data `permohonan_masuk` (kolom `status`), tidak perlu tabel approval terpisah — lebih sederhana untuk dipahami.

## 2. Struktur alur aplikasi
```
Auth (login)
 └─ Dashboard (ringkasan jumlah data)
     ├─ Gse              -> CRUD data unit GSE (alat-alat ground support)
     ├─ Permohonan_masuk -> Input Permohonan masuk (permohonan izin GSE)
     ├─ Permohonan_keluar-> Input Permohonan keluar (balasan/izin resmi)
     └─ Approval         -> Pimpinan/admin menyetujui atau menolak Permohonan masuk
```

## 3. Cara menjalankan di komputer kamu (XAMPP/Laragon)
1. Pastikan PHP sudah aktif PostgreSQL extension (`pdo_pgsql` / `pgsql`) di `php.ini`.
2. Buat database di PostgreSQL:
   ```
   psql -U postgres -c "CREATE DATABASE gse_db;"
   psql -U postgres -d gse_db -f database/gse_db.sql
   ```
3. Cek `application/config/database.php`, sesuaikan `username`/`password` PostgreSQL di komputer kamu.
4. Letakkan folder project ini di `htdocs` (XAMPP) atau `www` (Laragon) dengan nama folder **PROJECT_GSE** (karena `base_url` di `application/config/config.php` sudah diset ke `http://localhost/PROJECT_GSE/`). Kalau mau pakai nama folder lain, ubah juga `base_url`-nya.
5. Buka `http://localhost/PROJECT_GSE/`

## 4. Akun login contoh (lihat data di `gse_db.sql`)
| Username | Password     | Role     |
|----------|---------------|----------|
| admin    | admin123      | admin    |
| petugas  | petugas123    | petugas  |
| pimpinan | pimpinan123   | pimpinan |

Hanya role `admin` dan `pimpinan` yang bisa mengakses menu **Approval**.

## 5. Catatan untuk pengembangan lanjut
- Password disimpan dengan **MD5** (mengikuti kode awal kamu) — ini cukup untuk tugas/belajar, tapi untuk aplikasi produksi sebaiknya diganti ke `password_hash()` / `password_verify()` (lebih aman).
- Belum ada fitur upload file lampiran Permohonan (PDF/scan Permohonan) — bisa ditambahkan kalau diperlukan.
- Belum ada halaman "Riwayat" approval (yang sudah Disetujui/Ditolak) secara terpisah dari Permohonan masuk yang masih Menunggu — bisa ditambah filter status di halaman Permohonan Masuk.
