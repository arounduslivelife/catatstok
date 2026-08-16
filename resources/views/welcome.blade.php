@extends('layouts.app')
@section('title', 'Aplikasi Manajemen Stok Ringkas')

@section('content')
<!-- Header Minimalis -->
<div style="display: flex; justify-content: space-between; align-items: center; padding: 20px;">
    <div style="display: flex; align-items: center; gap: 8px;">
        <i class="material-symbols-rounded" style="font-size: 32px; color: var(--primary);">inventory_2</i>
        <span style="font-size: 20px; font-weight: 700; color: var(--primary); letter-spacing: -0.5px;">CatatStok</span>
    </div>
    <a href="{{ route('login') }}" style="text-decoration: none; font-size: 15px; font-weight: 600; color: var(--text-main); background: rgba(0,0,0,0.05); padding: 8px 16px; border-radius: 20px;">Login</a>
</div>

<!-- Hero Section -->
<div style="text-align: center; padding: 40px 20px; background: linear-gradient(180deg, var(--bg-color) 0%, rgba(0,122,255,0.05) 100%); border-bottom: 1px solid var(--border);">
    <h1 style="font-size: 32px; line-height: 1.2; font-weight: 800; color: var(--text-main); margin-bottom: 16px; letter-spacing: -1px;">
        Kelola Stok Toko Anda Tanpa Ribet
    </h1>
    
    <p style="font-size: 16px; color: var(--text-muted); line-height: 1.6; margin-bottom: 32px; max-width: 400px; margin-left: auto; margin-right: auto;">
        Pantau barang masuk dan keluar langsung dari HP Anda. Cepat, akurat, dan cegah kecurangan karyawan.
    </p>
    
    <a href="https://wa.me/6285320335275" target="_blank" class="btn" style="background: #25D366; color: white; display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-size: 18px; padding: 18px 24px; border-radius: 14px; box-shadow: 0 8px 24px rgba(37,211,102,0.3); text-decoration: none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
        Hubungi via WhatsApp
    </a>
    <p style="font-size: 13px; color: var(--text-muted); margin-top: 16px;">Tertarik berlangganan? Konsultasikan dengan tim kami.</p>
</div>

<!-- Fitur Section -->
<div style="padding: 40px 20px;">
    <h2 style="font-size: 16px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 24px; text-align: center;">Kenapa Memilih CatatStok?</h2>
    
    <div class="card" style="margin: 0 0 16px 0; border-left: 4px solid var(--primary); padding: 20px;">
        <i class="material-symbols-rounded" style="font-size: 32px; color: var(--primary); margin-bottom: 12px;">bolt</i>
        <h3 style="font-size: 18px; margin-bottom: 8px;">Pencatatan Instan</h3>
        <p style="font-size: 14px; color: var(--text-muted); line-height: 1.5;">Catat barang masuk dan keluar secepat membalas pesan. Desain sangat ringan khusus untuk perangkat mobile.</p>
    </div>
    
    <div class="card" style="margin: 0 0 16px 0; border-left: 4px solid var(--warning); padding: 20px;">
        <i class="material-symbols-rounded" style="font-size: 32px; color: var(--warning); margin-bottom: 12px;">shield_locked</i>
        <h3 style="font-size: 18px; margin-bottom: 8px;">Aman dari Kecurangan</h3>
        <p style="font-size: 14px; color: var(--text-muted); line-height: 1.5;">Karyawan hanya bisa mencatat transaksi tanpa bisa mengubah data saldo sesuka hati. Semua terekam di sistem.</p>
    </div>
    
    <div class="card" style="margin: 0 0 32px 0; border-left: 4px solid var(--success); padding: 20px;">
        <i class="material-symbols-rounded" style="font-size: 32px; color: var(--success); margin-bottom: 12px;">monitoring</i>
        <h3 style="font-size: 18px; margin-bottom: 8px;">Laporan Real-time</h3>
        <p style="font-size: 14px; color: var(--text-muted); line-height: 1.5;">Lihat sisa stok dan riwayat mutasi mutlak 100% akurat kapan pun Anda butuhkan, di mana pun Anda berada.</p>
    </div>
</div>

<!-- Footer CTA -->
<div style="text-align: center; padding: 40px 20px; background: var(--card-bg); border-top: 1px solid var(--border);">
    <h2 style="font-size: 24px; font-weight: 800; margin-bottom: 16px;">Siap merapikan toko Anda?</h2>
    <a href="https://wa.me/6285320335275" target="_blank" class="btn" style="background: #25D366; color: white; text-decoration: none; padding: 16px 24px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
        Hubungi WhatsApp
    </a>
    
    <p style="margin-top: 40px; font-size: 13px; color: var(--text-muted);">
        &copy; {{ date('Y') }} CatatStok by AgungDS.<br>All rights reserved.
    </p>
</div>
@endsection
