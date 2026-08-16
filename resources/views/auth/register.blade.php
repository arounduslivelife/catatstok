@extends('layouts.app')
@section('title', 'Register SaaS')

@section('content')
<div class="card" style="margin-top: 40px;">
    <div style="text-align: center; margin-bottom: 24px;">
        <i class="material-symbols-rounded" style="font-size: 64px; color: var(--success);">add_business</i>
        <h1 style="color: var(--text-main); margin-bottom: 8px;">Daftar Perusahaan</h1>
        <p style="color: var(--text-muted);">Buat Workspace SaaS Anda</p>
    </div>
    
    @if($errors->any())
    <div style="color: white; background: var(--danger); padding: 10px; border-radius: 8px; margin-bottom: 16px;">
        {{ $errors->first() }}
    </div>
    @endif

    <form action="{{ url('/register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nama Pengguna / Perusahaan</label>
            <input type="text" name="username" class="form-control" placeholder="Misal: Toko Berkah" required>
        </div>
        <div class="form-group">
            <label>Nomor HP (Digunakan untuk Login)</label>
            <input type="text" name="phone" class="form-control" placeholder="08123456789" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
        </div>
        
        <button type="submit" class="btn btn-primary" style="margin-top: 10px; background: var(--success);">Buat Akun Sekarang</button>
    </form>
    
    <p style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted);">
        Sudah punya akun? <br>
        <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600; text-decoration: none; display: inline-block; margin-top: 8px;">Kembali ke Login</a>
    </p>
</div>
@endsection
