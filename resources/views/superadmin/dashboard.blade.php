@extends('layouts.app')
@section('title', 'SaaS Management - Super Admin')

@section('content')
<div class="top-header" style="background: var(--primary);">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="margin-bottom: 4px; font-size: 20px;">SaaS Management</h2>
            <p style="color: rgba(255,255,255,0.8); font-size: 14px;">Kelola langganan Workspace</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="{{ route('superadmin.wa-logs') }}" style="color: white; text-decoration: none; display: flex; flex-direction: column; align-items: center; opacity: 0.9;">
                <i class="material-symbols-rounded" style="font-size: 24px;">history</i>
                <span style="font-size: 10px;">WA Logs</span>
            </a>
            <form action="{{ url('/logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" style="background: none; border: none; color: white; cursor: pointer; opacity: 0.9; display: flex; flex-direction: column; align-items: center;">
                    <i class="material-symbols-rounded" style="font-size: 24px;">logout</i>
                    <span style="font-size: 10px;">Keluar</span>
                </button>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div style="margin: 20px 20px 0 20px; padding: 12px; background: var(--success); color: white; border-radius: 8px;">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="margin: 20px 20px 0 20px; padding: 12px; background: rgba(255, 59, 48, 0.1); color: var(--danger); border-radius: 8px;">
    {{ $errors->first() }}
</div>
@endif

<div style="padding: 20px; padding-bottom: 80px;">
    <!-- Bagian Ganti Password -->
    <div class="card" style="margin-bottom: 24px; padding: 16px; background: linear-gradient(135deg, rgba(0,0,0,0.02), rgba(0,0,0,0.05));">
        <h3 style="font-size: 16px; margin-bottom: 12px; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
            <i class="material-symbols-rounded" style="font-size: 20px;">lock_reset</i> Ganti Password Superadmin
        </h3>
        <form action="{{ route('superadmin.change-password') }}" method="POST" style="display: flex; gap: 8px;">
            @csrf
            <input type="password" name="password" class="form-control" placeholder="Masukkan password baru..." required style="flex: 1; min-width: 0; height: 44px; border-radius: 8px; border: 1px solid var(--border); padding: 0 12px;">
            <button type="submit" class="btn btn-primary" style="width: auto; flex: 0 0 auto; height: 44px; padding: 0 16px; border-radius: 8px; font-weight: 600;">Simpan</button>
        </form>
    </div>

    <!-- Bagian Pengaturan WA Gateway -->
    <div class="card" style="margin-bottom: 32px; padding: 16px; border-left: 4px solid var(--success); background: linear-gradient(135deg, rgba(0,0,0,0.02), rgba(0,0,0,0.05));">
        <h3 style="font-size: 16px; margin-bottom: 12px; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
            <i class="material-symbols-rounded" style="font-size: 20px; color: #25D366;">chat</i> Pengaturan WA Gateway (BotGateway)
        </h3>
        <form action="{{ route('superadmin.save-settings') }}" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
            @csrf
            <div>
                <label style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px; display: block;">API Key</label>
                <input type="text" name="wa_api_key" class="form-control" value="{{ \App\Models\Setting::where('key', 'wa_api_key')->value('value') }}" placeholder="Contoh: 1234567890..." required style="width: 100%; height: 44px; border-radius: 8px; border: 1px solid var(--border); padding: 0 12px;">
            </div>
            <div>
                <label style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px; display: block;">Sender Number (Device)</label>
                <input type="text" name="wa_sender_number" class="form-control" value="{{ \App\Models\Setting::where('key', 'wa_sender_number')->value('value') }}" placeholder="Contoh: 62888xxxx" required style="width: 100%; height: 44px; border-radius: 8px; border: 1px solid var(--border); padding: 0 12px;">
            </div>
            <button type="submit" class="btn btn-success" style="align-self: flex-start; background: #25D366; color: white; border: none; height: 44px; padding: 0 20px; border-radius: 8px; font-weight: 600; cursor: pointer;">Simpan Pengaturan WA</button>
        </form>
    </div>
    
    <h3 style="font-size: 18px; margin-bottom: 16px; color: var(--text-main);">Daftar Workspace</h3>

    @forelse($workspaces as $workspace)
    <div class="card" style="margin: 0 0 16px 0; padding: 16px; border-left: 4px solid {{ $workspace->status ? 'var(--success)' : 'var(--danger)' }};">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
            <div>
                <h3 style="font-size: 16px; margin-bottom: 4px; color: var(--text-main);">{{ $workspace->name }}</h3>
                <p style="font-size: 13px; color: var(--text-muted);">
                    <i class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle;">person</i> 
                    {{ optional($workspace->creator)->username }} ({{ optional($workspace->creator)->phone }})
                </p>
            </div>
            @if($workspace->status)
                <div>
                    <span style="background: rgba(52, 199, 89, 0.1); color: var(--success); padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">Aktif</span>
                    @if($workspace->is_trial)
                        <span style="background: rgba(0, 122, 255, 0.1); color: var(--primary); padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; margin-left: 4px;">Trial</span>
                    @endif
                </div>
            @else
                <div>
                    <span style="background: rgba(255, 59, 48, 0.1); color: var(--danger); padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">Suspend</span>
                    @if($workspace->is_trial)
                        <span style="background: rgba(0, 122, 255, 0.1); color: var(--primary); padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; margin-left: 4px;">Trial</span>
                    @endif
                </div>
            @endif
        </div>
        
        <div style="background: rgba(0,0,0,0.02); padding: 10px; border-radius: 8px; margin-bottom: 16px;">
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;"><span style="font-weight: 600; color: var(--primary);">{{ $workspace->products_count }}</span> Total Produk Tercatat</p>
            <p style="font-size: 13px; color: var(--text-muted);">
                Aktif s/d: 
                @if($workspace->subscription_ends_at)
                    <span style="font-weight: 600; color: {{ $workspace->subscription_ends_at < now() ? 'var(--danger)' : 'var(--text-main)' }};">
                        {{ \Carbon\Carbon::parse($workspace->subscription_ends_at)->format('d M Y') }}
                        @if($workspace->subscription_ends_at < now()) (Expired) @endif
                    </span>
                @else
                    <span style="font-weight: 600; color: var(--danger);">Belum Aktif (Menunggu)</span>
                @endif
            </p>
        </div>

        <form action="{{ url('/superadmin/workspaces/' . $workspace->id . '/set-date') }}" method="POST" style="margin-bottom: 12px; display: flex; gap: 8px;">
            @csrf
            <input type="date" name="end_date" class="form-control" style="flex: 1; padding: 8px 12px; height: auto;" required 
                   value="{{ $workspace->subscription_ends_at ? \Carbon\Carbon::parse($workspace->subscription_ends_at)->format('Y-m-d') : '' }}">
            <button type="submit" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px;">Set Tanggal</button>
        </form>

        <div style="display: flex; gap: 8px;">
            <form action="{{ url('/superadmin/workspaces/' . $workspace->id . '/toggle') }}" method="POST" style="flex: 1;">
                @csrf
                <button type="submit" class="btn" style="width: 100%; padding: 10px; font-size: 13px; background: {{ $workspace->status ? 'var(--danger)' : 'var(--success)' }}; color: white; font-weight: 600;">
                    <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle; margin-right: 4px;">{{ $workspace->status ? 'block' : 'check_circle' }}</i>
                    {{ $workspace->status ? 'Suspend' : 'Aktifkan' }}
                </button>
            </form>
            
            <form action="{{ url('/superadmin/workspaces/' . $workspace->id . '/start-trial') }}" method="POST" style="flex: 1;">
                @csrf
                <button type="submit" class="btn" style="width: 100%; padding: 10px; font-size: 13px; background: rgba(0,122,255,0.1); color: var(--primary); font-weight: 600;">
                    <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle; margin-right: 4px;">rocket_launch</i>
                    Tandai Trial
                </button>
            </form>
        </div>
        
        <div style="margin-top: 12px;">
            <a href="{{ route('superadmin.logs', $workspace->id) }}" class="btn" style="display: block; width: 100%; padding: 10px; font-size: 13px; background: #333; color: white; font-weight: 600; text-align: center; text-decoration: none;">
                <i class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle; margin-right: 4px;">list_alt</i>
                Lihat Log Aktivitas
            </a>
        </div>
    </div>
    @empty
    <div class="card" style="text-align: center; padding: 40px 20px;">
        <i class="material-symbols-rounded" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;">business</i>
        <h3 style="margin-bottom: 8px;">Belum ada Perusahaan</h3>
        <p style="color: var(--text-muted); font-size: 14px;">Toko yang mendaftar akan muncul di sini.</p>
    </div>
    @endforelse
</div>
@endsection
