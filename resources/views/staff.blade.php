@extends('layouts.app')
@section('title', 'Manajemen Karyawan')

@section('content')
<div class="top-header">
    <h2 style="margin-bottom: 4px;">Manajemen Karyawan</h2>
    <p style="color: var(--text-muted); font-size: 14px;">Tambah staff untuk membantu kelola stok.</p>
</div>

@if(session('success'))
<div style="margin: 20px; padding: 12px; background: var(--success); color: white; border-radius: 8px;">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="margin: 20px; padding: 12px; background: var(--danger); color: white; border-radius: 8px;">
    {{ $errors->first() }}
</div>
@endif

<div class="card" style="margin: 20px;">
    <h3 style="margin-bottom: 16px; font-size: 16px;">Daftarkan Staff Baru</h3>
    <form action="{{ url('/staff') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nomor HP (Untuk Login Staff)</label>
            <input type="text" name="phone" class="form-control" placeholder="08..." required>
        </div>
        <div class="form-group">
            <label>Nama Staff</label>
            <input type="text" name="username" class="form-control" placeholder="Nama lengkap..." required>
        </div>
        <div class="form-group">
            <label>Password (Untuk Staff)</label>
            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 8px;">Daftarkan Staff</button>
    </form>
</div>

<div style="padding: 0 20px 80px 20px;">
    <h3 style="margin-bottom: 16px; font-size: 16px;">Daftar Staff ({{ $staff->count() }})</h3>
    
    @forelse($staff as $member)
    <div class="card" style="margin: 0 0 12px 0; padding: 16px; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 20px; background: rgba(0,122,255,0.1); display: flex; align-items: center; justify-content: center;">
                <i class="material-symbols-rounded" style="color: var(--primary);">person</i>
            </div>
            <div>
                <p style="font-weight: 600; font-size: 15px; margin-bottom: 2px;">{{ $member->username }}</p>
                <p style="font-size: 13px; color: var(--text-muted);">{{ $member->phone }}</p>
            </div>
        </div>
        <div>
            <span style="font-size: 11px; background: var(--border); padding: 4px 10px; border-radius: 12px; font-weight: 600;">STAFF</span>
        </div>
    </div>
    @empty
    <div class="card" style="text-align: center; padding: 30px;">
        <p style="color: var(--text-muted);">Belum ada karyawan yang terdaftar.</p>
    </div>
    @endforelse
</div>
@endsection
