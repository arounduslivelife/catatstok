@extends('layouts.app')
@section('title', 'Log Pesan WA Gateway')

@section('content')
<div class="top-header" style="background: var(--primary);">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="margin-bottom: 4px; font-size: 20px;">Log WA Gateway</h2>
            <p style="color: rgba(255,255,255,0.8); font-size: 14px;">Riwayat pengiriman pesan WhatsApp</p>
        </div>
        <a href="{{ route('superadmin.dashboard') }}" style="color: white; text-decoration: none; display: flex; align-items: center; gap: 4px; background: rgba(255,255,255,0.2); padding: 6px 12px; border-radius: 20px; font-size: 14px;">
            <i class="material-symbols-rounded" style="font-size: 18px;">arrow_back</i> Kembali
        </a>
    </div>
</div>

<div style="padding: 20px; padding-bottom: 80px;">
    @forelse($logs as $log)
    <div class="card" style="margin-bottom: 16px; padding: 16px; border-left: 4px solid {{ $log->status === 'success' ? 'var(--success)' : 'var(--danger)' }};">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
            <div>
                <h3 style="font-size: 16px; margin-bottom: 4px; color: var(--text-main);">
                    <i class="material-symbols-rounded" style="font-size: 18px; vertical-align: middle; color: #25D366;">chat</i> {{ $log->phone_number }}
                </h3>
                <p style="font-size: 12px; color: var(--text-muted);">
                    <i class="material-symbols-rounded" style="font-size: 14px; vertical-align: middle;">schedule</i> {{ $log->created_at->format('d M Y, H:i') }}
                </p>
            </div>
            <div>
                @if($log->status === 'success')
                    <span style="background: rgba(52, 199, 89, 0.1); color: var(--success); padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">Berhasil</span>
                @else
                    <span style="background: rgba(255, 59, 48, 0.1); color: var(--danger); padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">Gagal</span>
                @endif
            </div>
        </div>
        
        <div style="background: rgba(0,0,0,0.03); padding: 12px; border-radius: 8px; margin-bottom: 12px;">
            <p style="font-size: 13px; color: var(--text-main); white-space: pre-wrap; font-family: monospace;">{{ $log->message }}</p>
        </div>

        <div style="background: {{ $log->status === 'success' ? 'rgba(52, 199, 89, 0.05)' : 'rgba(255, 59, 48, 0.05)' }}; padding: 12px; border-radius: 8px;">
            <p style="font-size: 12px; font-weight: 600; color: {{ $log->status === 'success' ? 'var(--success)' : 'var(--danger)' }}; margin-bottom: 4px;">Respons Server:</p>
            <p style="font-size: 12px; color: var(--text-muted); word-break: break-all; font-family: monospace;">{{ $log->response_data ?? 'Tidak ada data respons.' }}</p>
        </div>
    </div>
    @empty
    <div class="card" style="text-align: center; padding: 40px 20px;">
        <i class="material-symbols-rounded" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;">history</i>
        <h3 style="margin-bottom: 8px;">Belum Ada Log</h3>
        <p style="color: var(--text-muted); font-size: 14px;">Log pengiriman pesan WhatsApp akan muncul di sini.</p>
    </div>
    @endforelse

    <div style="margin-top: 20px;">
        {{ $logs->links() }}
    </div>
</div>
@endsection
