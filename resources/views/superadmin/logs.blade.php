@extends('layouts.app')
@section('title', 'Log Aktivitas Workspace')

@section('content')
<div class="top-header" style="background: var(--primary); padding-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div style="color: white;">
            <p style="font-size: 13px; opacity: 0.9;">Log Aktivitas untuk</p>
            <h2 style="font-size: 20px; font-weight: 700;">{{ $workspace->name }}</h2>
        </div>
        <a href="{{ route('superadmin.dashboard') }}" style="color: white; opacity: 0.9; text-decoration: none;">
            <i class="material-symbols-rounded" style="font-size: 28px;">arrow_back</i>
        </a>
    </div>
</div>

<div style="padding: 20px;">
    @if($logs->count() > 0)
        <div class="card" style="padding: 0; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 1px solid var(--border); text-align: left;">
                        <th style="padding: 12px 16px; font-size: 13px; color: var(--text-muted);">User</th>
                        <th style="padding: 12px 16px; font-size: 13px; color: var(--text-muted);">Aktivitas</th>
                        <th style="padding: 12px 16px; font-size: 13px; color: var(--text-muted);">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 12px 16px; font-size: 14px; font-weight: 500;">
                            {{ optional($log->user)->username ?? 'Unknown' }}<br>
                            <span style="font-size: 11px; color: var(--text-muted);">{{ optional($log->user)->role }}</span>
                        </td>
                        <td style="padding: 12px 16px; font-size: 14px;">
                            {{ $log->description }}
                        </td>
                        <td style="padding: 12px 16px; font-size: 13px; color: var(--text-muted);">
                            {{ $log->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 20px;">
            {{ $logs->links() }}
        </div>
    @else
        <p style="text-align: center; color: var(--text-muted); padding: 40px 0;">Belum ada log aktivitas dari workspace ini.</p>
    @endif
</div>
@endsection
