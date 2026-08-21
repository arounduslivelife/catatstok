@extends('layouts.app')
@section('title', 'Edit Produk')

@section('content')
<div class="top-header">
    <a href="{{ url('/products') }}" style="color: var(--primary); text-decoration: none; font-size: 14px; display: inline-flex; align-items: center; margin-bottom: 8px;">
        <i class="material-symbols-rounded" style="font-size: 16px; margin-right: 4px;">arrow_back</i> Kembali
    </a>
    <h2 style="margin-bottom: 4px;">Edit Produk</h2>
    <p style="color: var(--text-muted); font-size: 14px;">Ubah Data Produk</p>
</div>

<div class="card" style="margin: 20px;">
    <form action="{{ url('/products/' . $product->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Kode Produk</label>
            <input type="text" name="code" class="form-control" value="{{ $product->code }}" required>
        </div>
        <div class="form-group">
            <label>Nama Produk</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>
        <div class="form-group">
            <label>Satuan</label>
            <input type="text" name="unit" class="form-control" value="{{ $product->unit }}" required>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 8px;">Simpan Perubahan</button>
    </form>
</div>
@endsection
