@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
<div class="top-header" style="background: var(--primary); padding-bottom: 30px;">
    <h2 style="margin-bottom: 12px; color: white;">Laporan</h2>
    
    <!-- Segmented Control Tabs -->
    <div style="display: flex; background: rgba(0,0,0,0.15); border-radius: 12px; padding: 4px;">
        <button id="btnTabStok" onclick="switchTab('stok')" style="flex: 1; padding: 10px; border: none; border-radius: 10px; background: white; color: var(--primary); font-weight: 600; font-size: 14px; transition: 0.2s; cursor: pointer;">
            Laporan Stok
        </button>
        <button id="btnTabRiwayat" onclick="switchTab('riwayat')" style="flex: 1; padding: 10px; border: none; border-radius: 10px; background: transparent; color: white; font-weight: 600; font-size: 14px; transition: 0.2s; cursor: pointer;">
            Riwayat Transaksi
        </button>
    </div>
</div>

<div style="padding: 20px 20px 80px 20px;">
    
    @if(session('success'))
    <div style="padding: 12px; background: var(--success); color: white; border-radius: 8px; margin-bottom: 16px;">
        {{ session('success') }}
    </div>
    @endif
    
    <!-- TAB: LAPORAN STOK -->
    <div id="tabStok">
        <div style="margin-bottom: 16px;">
            <input type="text" id="searchReport" class="form-control" placeholder="Cari nama produk..." style="width: 100%; padding: 14px 16px; border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); font-size: 15px;" onkeyup="filterReports()">
        </div>

        <div id="reportList">
            @forelse($products as $product)
            <div class="card report-item" style="margin-bottom: 12px; padding: 16px; cursor: pointer; transition: 0.2s;" onclick="openModal('modal-{{ $product->id }}')" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 class="product-name" style="margin: 0 0 4px 0; font-size: 16px; color: var(--text-main);">{{ $product->name }}</h3>
                        <p style="margin: 0; font-size: 13px; color: var(--text-muted);">{{ $product->code }}</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="margin: 0; font-size: 12px; color: var(--text-muted); margin-bottom: 2px;">Saldo Stok</p>
                        <span style="font-size: 18px; font-weight: 700; color: {{ $product->stock > 0 ? 'var(--success)' : ($product->stock < 0 ? 'var(--danger)' : 'var(--text-main)') }};">
                            {{ $product->stock }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Modal Mutasi -->
            <div id="modal-{{ $product->id }}" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: flex-end; justify-content: center;">
                <div style="background: white; width: 100%; max-width: 500px; max-height: 85vh; border-radius: 20px 20px 0 0; display: flex; flex-direction: column; animation: slideUp 0.3s ease-out;">
                    <div style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h3 style="margin: 0; font-size: 18px;">Riwayat Mutasi</h3>
                            <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">{{ $product->name }}</p>
                        </div>
                        <button onclick="closeModal('modal-{{ $product->id }}')" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px;">
                            <i class="material-symbols-rounded" style="font-size: 24px;">close</i>
                        </button>
                    </div>
                    
                    <div style="padding: 20px; overflow-y: auto;">
                        @php
                            $mutations = $product->transactionItems->sortByDesc(function($item) {
                                return optional($item->transaction)->created_at;
                            });
                        @endphp
                        
                        @forelse($mutations as $item)
                            @if($item->transaction)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; margin-bottom: 16px; border-bottom: 1px solid rgba(0,0,0,0.05);">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 36px; height: 36px; border-radius: 10px; background: {{ $item->transaction->type == 'IN' ? 'rgba(52, 199, 89, 0.1)' : 'rgba(255, 59, 48, 0.1)' }}; display: flex; align-items: center; justify-content: center;">
                                            <i class="material-symbols-rounded" style="font-size: 18px; color: {{ $item->transaction->type == 'IN' ? 'var(--success)' : 'var(--danger)' }};">
                                                {{ $item->transaction->type == 'IN' ? 'arrow_downward' : 'arrow_upward' }}
                                            </i>
                                        </div>
                                        <div>
                                            <p style="margin: 0; font-weight: 600; font-size: 14px;">{{ $item->transaction->type == 'IN' ? 'Masuk' : 'Keluar' }}</p>
                                            <p style="margin: 2px 0 0 0; font-size: 12px; color: var(--text-muted);">
                                                {{ $item->transaction->created_at->format('d M Y, H:i') }} &bull; {{ optional($item->transaction->creator)->username ?? 'Sistem' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                        <span style="font-weight: 700; font-size: 16px; color: {{ $item->transaction->type == 'IN' ? 'var(--success)' : 'var(--danger)' }};">
                                            {{ $item->transaction->type == 'IN' ? '+' : '-' }}{{ $item->qty }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div style="text-align: center; padding: 30px 0;">
                                <i class="material-symbols-rounded" style="font-size: 40px; color: var(--border); margin-bottom: 8px;">receipt_long</i>
                                <p style="color: var(--text-muted); font-size: 14px; margin: 0;">Belum ada riwayat mutasi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @empty
            <div class="card" style="text-align: center; padding: 40px 20px;">
                <i class="material-symbols-rounded" style="font-size: 48px; color: var(--border); margin-bottom: 16px;">inventory_2</i>
                <h3 style="margin-bottom: 8px;">Belum ada Produk</h3>
                <p style="color: var(--text-muted); font-size: 14px;">Produk Anda akan muncul di sini.</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- TAB: RIWAYAT TRANSAKSI -->
    <div id="tabRiwayat" style="display: none;">
        <div style="margin-bottom: 16px;">
            <input type="text" id="searchHistory" class="form-control" placeholder="Cari catatan / kasir / produk..." style="width: 100%; padding: 14px 16px; border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); font-size: 15px;" onkeyup="filterHistory()">
        </div>
        
        <div id="historyList">
            @forelse($transactions as $trx)
            <div class="card trx-item" style="margin: 0 0 16px 0; padding: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
                    <div style="display: flex; align-items: center;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: {{ $trx->type == 'IN' ? 'rgba(52, 199, 89, 0.1)' : 'rgba(255, 59, 48, 0.1)' }}; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                            <i class="material-symbols-rounded" style="color: {{ $trx->type == 'IN' ? 'var(--success)' : 'var(--danger)' }}; font-size: 18px;">
                                {{ $trx->type == 'IN' ? 'arrow_downward' : 'arrow_upward' }}
                            </i>
                        </div>
                        <div class="trx-desc">
                            <span style="font-weight: bold; font-size: 14px; color: {{ $trx->type == 'IN' ? 'var(--success)' : 'var(--danger)' }};">
                                {{ $trx->type == 'IN' ? 'BARANG MASUK' : 'BARANG KELUAR' }}
                            </span>
                            <span style="display: block; font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                                Oleh: {{ optional($trx->creator)->username ?? 'Sistem' }} &bull; {{ $trx->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                    </div>
                    
                    @if(auth()->user()->role === 'owner')
                    <div style="display: flex; gap: 4px;">
                        <a href="{{ url('/transactions/' . $trx->id . '/edit') }}" style="color: var(--primary); padding: 6px; border-radius: 6px; display: inline-flex; background: rgba(0,122,255,0.1);">
                            <i class="material-symbols-rounded" style="font-size: 18px;">edit</i>
                        </a>
                        <form action="{{ url('/transactions/' . $trx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Saldo stok akan disesuaikan kembali.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: var(--danger); background: rgba(255,59,48,0.1); border: none; padding: 6px; border-radius: 6px; display: inline-flex; cursor: pointer;">
                                <i class="material-symbols-rounded" style="font-size: 18px;">delete</i>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                
                @foreach($trx->items as $item)
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span class="trx-item-name" style="font-size: 15px;">{{ optional($item->product)->name ?? 'Produk Dihapus' }}</span>
                    <span style="font-weight: 600; font-size: 15px;">{{ $trx->type == 'IN' ? '+' : '-' }}{{ $item->qty }}</span>
                </div>
                @endforeach
                
                @if($trx->notes)
                <div class="trx-notes" style="margin-top: 12px; font-size: 13px; color: var(--text-muted); font-style: italic;">
                    "{{ $trx->notes }}"
                </div>
                @endif
                
                @if($trx->photo_url)
                <div style="margin-top: 16px;">
                    <img src="{{ url($trx->photo_url) }}" style="width: 100%; border-radius: 8px; object-fit: cover; max-height: 200px;">
                </div>
                @endif
            </div>
            @empty
            <div class="card" style="text-align: center; padding: 40px 20px;">
                <i class="material-symbols-rounded" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;">receipt_long</i>
                <h3 style="margin-bottom: 8px;">Belum ada Transaksi</h3>
                <p style="color: var(--text-muted); font-size: 14px;">Transaksi masuk atau keluar akan muncul di sini.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}
</style>

<script>
function switchTab(tab) {
    if(tab === 'stok') {
        document.getElementById('tabStok').style.display = 'block';
        document.getElementById('tabRiwayat').style.display = 'none';
        
        document.getElementById('btnTabStok').style.background = 'white';
        document.getElementById('btnTabStok').style.color = 'var(--primary)';
        
        document.getElementById('btnTabRiwayat').style.background = 'transparent';
        document.getElementById('btnTabRiwayat').style.color = 'white';
    } else {
        document.getElementById('tabStok').style.display = 'none';
        document.getElementById('tabRiwayat').style.display = 'block';
        
        document.getElementById('btnTabRiwayat').style.background = 'white';
        document.getElementById('btnTabRiwayat').style.color = 'var(--primary)';
        
        document.getElementById('btnTabStok').style.background = 'transparent';
        document.getElementById('btnTabStok').style.color = 'white';
    }
}

function filterReports() {
    let input = document.getElementById('searchReport').value.toLowerCase();
    let items = document.querySelectorAll('.report-item');
    
    items.forEach(item => {
        let name = item.querySelector('.product-name').innerText.toLowerCase();
        if(name.includes(input)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function filterHistory() {
    let input = document.getElementById('searchHistory').value.toLowerCase();
    let items = document.querySelectorAll('.trx-item');
    
    items.forEach(item => {
        let text = item.innerText.toLowerCase();
        if(text.includes(input)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function openModal(id) {
    let modal = document.getElementById(id);
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    let modal = document.getElementById(id);
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}
</script>
@endsection
