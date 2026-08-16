# Panduan Agent untuk CatatStok App

File ini berisi aturan dan panduan untuk AI Agent dalam memodifikasi dan mengembangkan repository `catatstok`.

## 1. Stack Teknologi
- **Backend**: PHP (Localhost, Apache, tidak menggunakan XAMPP), MariaDB.
- **Database**: Nama database `catatstok_db`, User `root`, Password `admin`.
- **Frontend**: WebApp (Mobile First) & Flutter Native (Android/iOS).

## 2. Multi-Tenancy (SaaS)
- Setiap tabel utama (seperti `products`, `transactions`, `users`) HARUS memiliki kolom `workspace_id`.
- Setiap query (SELECT, UPDATE, DELETE) dari end-user (bukan superadmin) HARUS selalu memfilter berdasarkan `workspace_id` user yang sedang login untuk memastikan tidak ada kebocoran data antar perusahaan.

## 3. Aturan Transaksi
- Edit transaksi (Stok Masuk / Stok Keluar) HANYA diizinkan jika `user_id` yang sedang login sama dengan `created_by` dari transaksi tersebut. Validasi ini harus diterapkan ketat di sisi backend API.

## 4. UI/UX & Desain
- Lihat `design.md` untuk pedoman visual dan desain. Utamakan aksesibilitas untuk pengguna usia lanjut.

## 5. Komunikasi Data
- Komunikasi antara WebApp / Flutter App dengan Backend PHP menggunakan format JSON (REST API).

## 6. Prosedur Pengembangan
- Gunakan `progress.json` untuk mencatat penyelesaian tugas dan mengelola versi API/App.
- Jangan pernah menghapus file atau kolom database lama tanpa memastikan tidak ada versi aplikasi yang rusak (backward compatibility).

## 7. Pencatatan Aktivitas (Logging)
- **Semua tindakan user harus tercatat di log.** Setiap aksi seperti membuat produk, menambah/mengedit stok, dan mengubah profil harus menyisipkan rekaman ke dalam tabel `activity_logs` (berisi user_id, action, deskripsi, ip_address, dan timestamp).
