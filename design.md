# Pedoman Desain (Design Guidelines) - CatatStok

Dokumen ini mendefinisikan aturan UI/UX untuk aplikasi CatatStok (baik versi Web maupun Flutter Native).

## 1. Filosofi Desain
Aplikasi ini ditujukan sebagai alat produktivitas yang dapat diandalkan oleh berbagai kalangan usia, termasuk lansia. Oleh karena itu, antarmuka harus:
- **Jelas dan Terbaca** (Accessible).
- **Intuitif** (Mudah dipelajari tanpa tutorial rumit).
- **Konsisten** antara versi Web dan Mobile Native.

## 2. Tipografi & Ukuran
- **Ukuran Font Dasar**: Minimum 16px (atau setara di Flutter) untuk body text.
- **Berat Font**: Gunakan Medium/Bold untuk label penting. Hindari font yang terlalu tipis (Light/Thin).
- **Contrast Ratio**: Warna teks dan background harus memiliki kontras tinggi (misal: teks hitam/abu tua di atas background putih/abu sangat terang). Hindari abu-abu muda di atas putih.

## 3. Komponen UI Interaktif
- **Ukuran Touch Target (Tombol/Area Sentuh)**: Minimal 48x48 dp untuk menghindari salah pencet oleh pengguna dengan presisi sentuhan yang berkurang.
- **Ikon + Teks**: Sebaiknya ikon didampingi label teks agar tidak ambigu bagi lansia (misal ikon disket disertakan teks "Simpan").

## 4. Navigasi
- Menggunakan **Bottom Navigation Bar** baik di WebApp versi mobile maupun di Flutter Native.
- Menu utama pada Bottom Nav: 
  - Dashboard
  - Produk
  - Masuk (Stok Masuk)
  - Keluar (Stok Keluar)
  - Laporan

## 5. Formulir & Input
- Input text harus memiliki *border* yang jelas.
- Berikan label pada setiap input, jangan hanya mengandalkan *placeholder* yang hilang saat mengetik.
- Tampilkan pesan error dengan warna merah yang mencolok dan font tebal.

## 6. Konsistensi Web & Flutter
- WebApp didesain secara spesifik sebagai "Mobile Web App" yang ukurannya disesuaikan menyerupai layar handphone.
- Warna tema (Primary, Secondary, Background) harus sama persis kode Hex-nya antara Web dan Flutter.
- Layout halaman Dashboard, Daftar Produk, dan Transaksi dibuat identik tata letaknya.
