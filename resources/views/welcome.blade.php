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
    <div style="display: inline-block; background: rgba(0,122,255,0.1); color: var(--primary); padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 700; margin-bottom: 20px; letter-spacing: 0.5px;">
        SISTEM SAAS TERBAIK 2026
    </div>
    
    <h1 style="font-size: 32px; line-height: 1.2; font-weight: 800; color: var(--text-main); margin-bottom: 16px; letter-spacing: -1px;">
        Kelola Stok Toko Anda Tanpa Ribet
    </h1>
    
    <p style="font-size: 16px; color: var(--text-muted); line-height: 1.6; margin-bottom: 32px; max-width: 400px; margin-left: auto; margin-right: auto;">
        Pantau barang masuk dan keluar langsung dari HP Anda. Cepat, akurat, dan cegah kecurangan karyawan.
    </p>
    
    <a href="{{ url('/register') }}" class="btn btn-primary" style="display: block; font-size: 18px; padding: 18px 24px; border-radius: 14px; box-shadow: 0 8px 24px rgba(0,122,255,0.3); text-decoration: none;">
        Mulai Coba Gratis 14 Hari
    </a>
    <p style="font-size: 13px; color: var(--text-muted); margin-top: 16px;">Tanpa kartu kredit. Daftar dalam 1 menit.</p>
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
    <a href="{{ url('/register') }}" class="btn" style="background: var(--text-main); color: var(--bg-color); text-decoration: none; padding: 16px 24px; border-radius: 12px; display: inline-block;">
        Daftar Sekarang
    </a>
    
    <p style="margin-top: 40px; font-size: 13px; color: var(--text-muted);">
        &copy; {{ date('Y') }} CatatStok by AgungDS.<br>All rights reserved.
    </p>
</div>
@endsection
