@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="card" style="margin-top: 80px;">
    <div style="text-align: center; margin-bottom: 24px;">
        <i class="material-symbols-rounded" style="font-size: 64px; color: var(--primary);">inventory_2</i>
        <h1 style="color: var(--primary); margin-bottom: 8px;">CatatStok</h1>
        <p style="color: var(--text-muted);">Aplikasi Manajemen Stok Ringkas</p>
    </div>
    
    @if($errors->any())
    <div style="margin-bottom: 20px; padding: 12px; background: rgba(255, 59, 48, 0.1); color: var(--danger); border: 1px solid var(--danger); border-radius: 8px; font-size: 14px; text-align: center;">
        {{ $errors->first() }}
    </div>
    @endif
    
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nomor HP</label>
            <input type="text" name="phone" class="form-control" placeholder="08123456789" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        
        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Login</button>
    </form>
    
    <p style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted);">
        Belum punya akun SaaS? <br>
        <a href="{{ url('/register') }}" style="color: var(--primary); font-weight: 600; text-decoration: none; display: inline-block; margin-top: 8px;">Daftar Perusahaan Baru</a>
    </p>
</div>
@endsection
