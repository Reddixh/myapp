@extends('layouts.app')

@section('content')
<style>
    .page-title { font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
    .page-sub { font-size: 13px; color: #64748b; margin-bottom: 20px; }
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 16px; }
    .notif-item { display: flex; gap: 14px; align-items: flex-start; padding: 16px 20px; border-bottom: 1px solid #f1f5f9; transition: background 0.15s; }
    .notif-item:last-child { border-bottom: none; }
    .notif-item.unread { background: #f0f7ff; }
    .notif-item:hover { background: #f8fafc; }
    .notif-icon { width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    .notif-title { font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
    .notif-msg { font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 6px; }
    .notif-meta { font-size: 11px; color: #94a3b8; }
    .unread-dot { width: 10px; height: 10px; border-radius: 50%; background: #185FA5; flex-shrink: 0; margin-top: 6px; }
    .empty-state { text-align: center; padding: 60px; color: #94a3b8; }
</style>

<div class="page-title">All Notifications</div>
<div class="page-sub">All messages and alerts from your departments.</div>

<div class="card">
    @forelse($notifications as $notif)
        <div class="notif-item {{ !$notif->is_read ? 'unread' : '' }}">
            <div class="notif-icon" style="background:{{ $notif->type === 'success' ? '#dcfce7' : ($notif->type === 'danger' ? '#fee2e2' : ($notif->type === 'warning' ? '#fef9c3' : '#E6F1FB')) }}">
                {{ $notif->type === 'success' ? '✅' : ($notif->type === 'danger' ? '❌' : ($notif->type === 'warning' ? '⚠️' : 'ℹ️')) }}
            </div>
            <div style="flex:1">
                <div class="notif-title">{{ $notif->title }}</div>
                <div class="notif-msg">{{ $notif->message }}</div>
                <div class="notif-meta">
                    From: <strong>{{ $notif->from }}</strong>
                    &nbsp;•&nbsp; {{ $notif->created_at->format('d M Y, h:i A') }}
                    &nbsp;•&nbsp; {{ $notif->created_at->diffForHumans() }}
                </div>
            </div>
            @if(!$notif->is_read)
                <div class="unread-dot"></div>
            @endif
        </div>
    @empty
        <div class="empty-state">
            🔔 No notifications yet!<br>
            <span style="font-size:13px">You'll see messages from departments here.</span>
        </div>
    @endforelse
</div>

{{ $notifications->links() }}

@endsection