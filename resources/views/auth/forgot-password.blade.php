@extends('layouts.app')
@section('title', 'Lupa Password')

@section('content')
<div class="card" style="margin-top: 80px;">
    <div style="text-align: center; margin-bottom: 24px;">
        <i class="material-symbols-rounded" style="font-size: 64px; color: var(--primary);">lock_reset</i>
        <h1 style="color: var(--primary); margin-bottom: 8px;">Lupa Password</h1>
        <p style="color: var(--text-muted); font-size: 14px;">Masukkan nomor HP terdaftar. Password baru akan dikirimkan ke WhatsApp Anda.</p>
    </div>
    
    @if(session('success'))
    <div style="margin-bottom: 20px; padding: 12px; background: rgba(52, 199, 89, 0.1); color: var(--success); border: 1px solid var(--success); border-radius: 8px; font-size: 14px; text-align: center;">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div style="margin-bottom: 20px; padding: 12px; background: rgba(255, 59, 48, 0.1); color: var(--danger); border: 1px solid var(--danger); border-radius: 8px; font-size: 14px; text-align: center;">
        {{ $errors->first() }}
    </div>
    @endif
    
    <form action="{{ route('forgot-password') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nomor HP (WhatsApp)</label>
            <input type="text" name="phone" class="form-control" placeholder="Contoh: 08123456789" required>
        </div>
        
        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Kirim Password Baru</button>
    </form>
    
    <p style="text-align: center; margin-top: 24px; font-size: 14px;">
        <a href="{{ route('login') }}" style="color: var(--text-muted); font-weight: 600; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 4px;">
            <i class="material-symbols-rounded" style="font-size: 16px;">arrow_back</i> Kembali ke Login
        </a>
    </p>
</div>
@endsection
