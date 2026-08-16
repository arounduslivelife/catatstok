@extends('layouts.app')
@section('title', 'Produk')

@section('content')
<div class="top-header">
    <h2 style="margin-bottom: 4px;">Daftar Produk</h2>
    <p style="color: var(--text-muted); font-size: 14px;">Manajemen Data Produk</p>
</div>

@if(session('success'))
<div style="margin: 20px; padding: 12px; background: var(--success); color: white; border-radius: 8px;">
    {{ session('success') }}
</div>
@endif

<div class="card" style="margin: 20px;">
    <h3 style="margin-bottom: 16px; font-size: 16px;">Tambah Produk Baru</h3>
    <form action="{{ url('/products') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Kode Produk</label>
            <input type="text" name="code" class="form-control" placeholder="Misal: BRG-001" required>
        </div>
        <div class="form-group">
            <label>Nama Produk</label>
            <input type="text" name="name" class="form-control" placeholder="Misal: Indomie Goreng" required>
        </div>
        <div class="form-group">
            <label>Satuan</label>
            <input type="text" name="unit" class="form-control" placeholder="Misal: Pcs, Dus, Kg" required>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 8px;">Simpan Produk</button>
    </form>
</div>

<div style="padding: 0 20px 80px 20px;">
    <h3 style="margin-bottom: 16px; font-size: 16px;">Semua Produk ({{ $products->count() }})</h3>
    
    @forelse($products as $product)
    <div class="card" style="margin: 0 0 12px 0; padding: 16px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <p style="font-weight: 600; font-size: 15px;">{{ $product->name }}</p>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">{{ $product->code }} &bull; Satuan: {{ $product->unit }}</p>
        </div>
        <div style="text-align: right;">
            <p style="font-size: 12px; color: var(--text-muted);">Stok Saat Ini</p>
            <p style="font-size: 20px; font-weight: bold; color: var(--primary);">{{ $product->stock }}</p>
        </div>
    </div>
    @empty
    <div class="card" style="text-align: center; padding: 30px;">
        <p style="color: var(--text-muted);">Belum ada data produk.</p>
    </div>
    @endforelse
</div>
@endsection
