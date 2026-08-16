@extends('layouts.app')
@section('title', 'Transaksi')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container .select2-selection--single {
    height: 50px !important;
    border: 2px solid var(--border) !important;
    border-radius: 12px !important;
    background-color: #f8f9fa !important;
    display: flex;
    align-items: center;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 48px !important;
    right: 10px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    font-weight: 500;
    color: var(--text-main);
    padding-left: 12px;
}
</style>

<div class="top-header" style="background: {{ $type == 'IN' ? 'var(--success)' : 'var(--danger)' }};">
    <h2 style="margin-bottom: 4px;">Catat Barang {{ $type == 'IN' ? 'Masuk' : 'Keluar' }}</h2>
    <p style="opacity: 0.9; font-size: 14px; color: white;">Tambahkan riwayat pergerakan stok</p>
</div>

@if($errors->any())
<div style="margin: 20px; padding: 12px; background: var(--danger); color: white; border-radius: 8px;">
    {{ $errors->first() }}
</div>
@endif

<div class="card" style="margin: 20px; margin-bottom: 80px;">
    <form action="{{ url('/transactions') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        
        <div id="product-list">
            <div class="product-row" style="display: flex; gap: 12px; margin-bottom: 16px; align-items: flex-end; width: 100%;">
                <div style="flex: 3; min-width: 0;">
                    <label style="font-weight: 600; font-size: 14px; color: var(--text-main); margin-bottom: 6px; display: block;">Pilih Produk</label>
                    <select name="product_id[]" class="form-control select2" required style="width: 100%;">
                        <option value="">-- Pilih --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->code }} - {{ $product->name }} (Stok: {{ $product->stock }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div style="flex: 1; min-width: 0;">
                    <label style="font-weight: 600; font-size: 14px; color: var(--text-main); margin-bottom: 6px; display: block;">Qty</label>
                    <input type="number" name="qty[]" class="form-control" min="1" required placeholder="0" style="height: 50px; border: 2px solid var(--border); border-radius: 12px; text-align: center; font-weight: bold; background-color: #f8f9fa;">
                </div>
                
                <button type="button" class="btn btn-remove" style="background: rgba(255, 59, 48, 0.1); color: var(--danger); border: none; border-radius: 12px; height: 50px; width: 50px; display: none; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer;" onclick="this.parentElement.remove()" onmouseover="this.style.background='var(--danger)'; this.style.color='white';" onmouseout="this.style.background='rgba(255, 59, 48, 0.1)'; this.style.color='var(--danger)';">
                    <i class="material-symbols-rounded">delete</i>
                </button>
            </div>
        </div>
        
        <button type="button" class="btn" style="width: 100%; background: linear-gradient(135deg, rgba(0,122,255,0.1), rgba(0,122,255,0.15)); color: var(--primary); margin-bottom: 24px; font-weight: 700; border-radius: 12px; border: 2px dashed rgba(0,122,255,0.3); padding: 14px; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 6px;" onclick="addRow()" onmouseover="this.style.background='rgba(0,122,255,0.2)';" onmouseout="this.style.background='linear-gradient(135deg, rgba(0,122,255,0.1), rgba(0,122,255,0.15))';">
            <i class="material-symbols-rounded" style="font-size: 20px;">add_circle</i> Tambah Produk Lain
        </button>
        
        <div class="form-group" style="margin-bottom: 24px;">
            <label style="font-weight: 600; font-size: 14px; color: var(--text-main); margin-bottom: 6px; display: block;">Keterangan / Catatan</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Opsional (Misal: Dari supplier X)" style="border: 2px solid var(--border); border-radius: 12px; padding: 12px; background-color: #f8f9fa; transition: 0.3s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'"></textarea>
        </div>
        
        <div class="form-group" style="margin-bottom: 24px;">
            <label style="font-weight: 600; font-size: 14px; color: var(--text-main); margin-bottom: 6px; display: block;">Bukti Foto (Opsional)</label>
            <div style="position: relative; overflow: hidden; border: 2px dashed var(--border); border-radius: 16px; background: #f8f9fa; padding: 24px 16px; text-align: center; cursor: pointer; transition: all 0.3s;" id="file-drop-area" onmouseover="this.style.borderColor='var(--primary)'; this.style.background='rgba(0,122,255,0.05)';" onmouseout="this.style.borderColor='var(--border)'; this.style.background='#f8f9fa';">
                <i class="material-symbols-rounded" style="font-size: 40px; color: var(--primary); margin-bottom: 8px;">add_a_photo</i>
                <p style="margin: 0; font-weight: 600; font-size: 14px; color: var(--text-main);" id="file-name">Ketuk untuk Mengunggah Foto</p>
                <p style="margin: 4px 0 0 0; font-size: 12px; color: var(--text-muted);">Foto otomatis dikompres ke format WebP</p>
                <input type="file" name="photo" id="photo-input" accept="image/*" capture="environment" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;" onchange="document.getElementById('file-name').innerText = this.files[0] ? this.files[0].name : 'Ketuk untuk Mengunggah Foto'; if(this.files[0]) { document.getElementById('file-drop-area').style.borderColor='var(--primary)'; }">
            </div>
        </div>
        
        <button type="submit" class="btn" style="width: 100%; padding: 16px; border-radius: 14px; font-weight: 700; font-size: 16px; background: {{ $type == 'IN' ? 'linear-gradient(135deg, #34C759, #28A745)' : 'linear-gradient(135deg, #FF3B30, #DC3545)' }}; color: white; box-shadow: 0 4px 12px {{ $type == 'IN' ? 'rgba(52, 199, 89, 0.4)' : 'rgba(255, 59, 48, 0.4)' }}; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; border: none;" onmousedown="this.style.transform='scale(0.97)';" onmouseup="this.style.transform='scale(1)';">
            <i class="material-symbols-rounded" style="font-size: 22px;">{{ $type == 'IN' ? 'download' : 'upload' }}</i> Simpan Transaksi {{ $type == 'IN' ? 'Masuk' : 'Keluar' }}
        </button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        width: '100%',
        placeholder: '-- Ketik / Pilih Produk --'
    });
});

function addRow() {
    const list = document.getElementById('product-list');
    const firstRow = list.querySelector('.product-row');
    
    // Matikan select2 di baris pertama sementara agar HTML aslinya bisa di-copy bersih
    $(firstRow).find('.select2').select2('destroy');
    
    const newRow = firstRow.cloneNode(true);
    newRow.querySelector('input').value = '';
    newRow.querySelector('select').value = '';
    
    const removeBtn = newRow.querySelector('.btn-remove');
    removeBtn.style.display = 'flex';
    // attach events to new button for hover
    removeBtn.onmouseover = function() { this.style.background='var(--danger)'; this.style.color='white'; };
    removeBtn.onmouseout = function() { this.style.background='rgba(255, 59, 48, 0.1)'; this.style.color='var(--danger)'; };
    
    list.appendChild(newRow);
    
    // Nyalakan ulang select2 untuk semua select di halaman
    $('.select2').select2({
        width: '100%',
        placeholder: '-- Ketik / Pilih Produk --'
    });
}
</script>
@endsection
