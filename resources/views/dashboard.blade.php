@extends('layouts.app')
@section('title', 'Beranda')

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
@keyframes scaleUp {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>

<div class="top-header" style="background: var(--primary); padding-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div style="color: white;">
            <p style="font-size: 13px; opacity: 0.9;">Halo, {{ auth()->user()->username }} 👋</p>
            <h2 style="font-size: 20px; font-weight: 700;">{{ optional(auth()->user()->workspace)->name ?? 'Workspace' }}</h2>
        </div>
        <div style="display: flex; gap: 8px;">
            @if(auth()->user()->role === 'owner')
            <a href="{{ url('/staff') }}" style="color: white; opacity: 0.9;">
                <i class="material-symbols-rounded" style="font-size: 28px;">group_add</i>
            </a>
            @endif
            <form action="{{ url('/logout') }}" method="POST">
                @csrf
                <button type="submit" style="background: none; border: none; color: white; cursor: pointer; opacity: 0.9;">
                    <i class="material-symbols-rounded" style="font-size: 28px;">logout</i>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="card" style="background: var(--primary); color: white;">
    <h3 style="font-size: 16px; font-weight: 500; opacity: 0.9;">Total Produk</h3>
    <h1 style="font-size: 36px; margin: 8px 0;">{{\App\Models\Product::count()}}</h1>
    <p style="font-size: 14px; opacity: 0.8;">Dalam pengawasan</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 0 20px;">
    <div class="card" style="margin: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px 16px;">
        <i class="material-symbols-rounded" style="font-size: 32px; color: var(--success); margin-bottom: 8px;">arrow_downward</i>
        <h3 style="font-size: 24px; color: var(--success);">{{\App\Models\Transaction::where('type', 'IN')->count()}}</h3>
        <p style="font-size: 12px; color: var(--text-muted);">Trx Masuk</p>
    </div>
    
    <div class="card" style="margin: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px 16px;">
        <i class="material-symbols-rounded" style="font-size: 32px; color: var(--danger); margin-bottom: 8px;">arrow_upward</i>
        <h3 style="font-size: 24px; color: var(--danger);">{{\App\Models\Transaction::where('type', 'OUT')->count()}}</h3>
        <p style="font-size: 12px; color: var(--text-muted);">Trx Keluar</p>
    </div>
</div>

<div style="padding: 20px;">
    <h2 style="font-size: 18px; margin-bottom: 16px;">Aktivitas Terbaru</h2>
    @php
        $logs = \App\Models\ActivityLog::with('user')->orderBy('created_at', 'desc')->take(5)->get();
    @endphp
    
    @if($logs->count() > 0)
        @foreach($logs as $log)
        <div style="display: flex; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 20px; background: rgba(0,122,255,0.1); display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                <i class="material-symbols-rounded" style="color: var(--primary); font-size: 20px;">history</i>
            </div>
            <div style="flex: 1;">
                <p style="font-weight: 600; font-size: 14px;">{{ optional($log->user)->username ?? 'User' }}</p>
                <p style="font-size: 13px; color: var(--text-muted);">{{ $log->description }}</p>
            </div>
            <div style="font-size: 11px; color: var(--text-muted);">
                {{ $log->created_at->diffForHumans() }}
            </div>
        </div>
        @endforeach
    @else
        <p style="color: var(--text-muted); font-size: 14px; text-align: center; padding: 20px 0;">Belum ada aktivitas tercatat.</p>
    @endif
</div>

<!-- Floating Action Button for Instant Stock Check -->
<div style="position: fixed; bottom: 100px; right: 20px; z-index: 100;">
    <button onclick="openInstantCheck()" style="width: 60px; height: 60px; border-radius: 30px; background: var(--primary); color: white; border: none; box-shadow: 0 4px 12px rgba(0,122,255,0.4); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.2s;" onmousedown="this.style.transform='scale(0.9)';" onmouseup="this.style.transform='scale(1)';">
        <i class="material-symbols-rounded" style="font-size: 32px;">search</i>
    </button>
</div>

<!-- Modal Cek Stok Instant -->
<div id="modal-instant-check" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: white; width: 100%; max-width: 400px; border-radius: 20px; padding: 24px; animation: scaleUp 0.2s ease-out; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; font-size: 18px;">Cek Stok Instant</h3>
            <button onclick="closeInstantCheck()" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;">
                <i class="material-symbols-rounded" style="font-size: 24px;">close</i>
            </button>
        </div>
        
        <div style="margin-bottom: 24px;">
            <label style="font-weight: 600; font-size: 14px; color: var(--text-main); margin-bottom: 8px; display: block;">Cari Produk</label>
            <select id="instantProductSelect" class="form-control select2" style="width: 100%;" onchange="showInstantStock(this)">
                <option value="">-- Ketik Nama / Kode --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">{{ $product->code }} - {{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div id="instantStockResult" style="display: none; text-align: center; padding: 24px 20px; background: #f8f9fa; border-radius: 16px; border: 2px dashed var(--border);">
            <p style="margin: 0 0 8px 0; font-size: 14px; color: var(--text-muted); font-weight: 500;">Saldo Stok Saat Ini</p>
            <h1 id="instantStockValue" style="margin: 0; font-size: 48px; color: var(--primary);">0</h1>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
function openInstantCheck() {
    document.getElementById('modal-instant-check').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    $('.select2').select2({
        dropdownParent: $('#modal-instant-check'),
        width: '100%',
        placeholder: '-- Ketik Nama / Kode --'
    });
}

function closeInstantCheck() {
    document.getElementById('modal-instant-check').style.display = 'none';
    document.getElementById('instantStockResult').style.display = 'none';
    document.body.style.overflow = 'auto';
    $('#instantProductSelect').val('').trigger('change');
}

function showInstantStock(selectElement) {
    if(!selectElement.value) {
        document.getElementById('instantStockResult').style.display = 'none';
        return;
    }
    const option = selectElement.options[selectElement.selectedIndex];
    const stock = parseInt(option.getAttribute('data-stock'));
    
    document.getElementById('instantStockResult').style.display = 'block';
    
    const valueEl = document.getElementById('instantStockValue');
    valueEl.innerText = stock;
    
    if(stock > 0) valueEl.style.color = 'var(--success)';
    else if(stock < 0) valueEl.style.color = 'var(--danger)';
    else valueEl.style.color = 'var(--text-main)';
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        closeInstantCheck();
    }
}
</script>
@endsection
