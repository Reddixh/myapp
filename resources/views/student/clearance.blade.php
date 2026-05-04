@extends('layouts.app')

@section('content')
<style>
    .page-title { font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 6px; }
    .page-sub { font-size: 13px; color: #64748b; margin-bottom: 20px; }
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px; }
    .card-title { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px; }
    .progress-label { display: flex; justify-content: space-between; font-size: 12px; color: #64748b; margin-bottom: 6px; }
    .progress-bar-bg { background: #f1f5f9; border-radius: 20px; height: 8px; margin-bottom: 16px; overflow: hidden; }
    .progress-bar-fill { height: 8px; border-radius: 20px; background: #185FA5; }
    .steps { display: flex; }
    .step { flex: 1; display: flex; flex-direction: column; align-items: center; position: relative; }
    .step:not(:last-child)::after { content: ''; position: absolute; top: 13px; left: 50%; width: 100%; height: 2px; background: #e2e8f0; z-index: 0; }
    .step.done:not(:last-child)::after { background: #185FA5; }
    .step-dot { width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; position: relative; z-index: 1; border: 2px solid #e2e8f0; background: #fff; color: #94a3b8; }
    .step.done .step-dot { background: #185FA5; border-color: #185FA5; color: #fff; }
    .step.active .step-dot { background: #E6F1FB; border-color: #185FA5; color: #0C447C; }
    .step-label { font-size: 10px; color: #94a3b8; margin-top: 5px; text-align: center; }
    .step.done .step-label { color: #185FA5; }
    .step.active .step-label { color: #0C447C; font-weight: 600; }
    .dept-list { display: flex; flex-direction: column; gap: 12px; }
    .dept-item { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 16px; }
    .dept-item.locked { background: #f8fafc; opacity: 0.6; }
    .dept-item.cleared { border-left: 4px solid #22c55e; }
    .dept-item.pending { border-left: 4px solid #f59e0b; }
    .dept-item.active-dept { border-left: 4px solid #185FA5; }
    .dept-number { width: 36px; height: 36px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; color: #94a3b8; flex-shrink: 0; }
    .dept-item.cleared .dept-number { background: #dcfce7; color: #16a34a; }
    .dept-item.pending .dept-number { background: #fef9c3; color: #ca8a04; }
    .dept-item.active-dept .dept-number { background: #E6F1FB; color: #0C447C; }
    .dept-info { flex: 1; }
    .dept-name { font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 3px; }
    .dept-item.locked .dept-name { color: #94a3b8; }
    .dept-desc { font-size: 12px; color: #64748b; }
    .dept-date { font-size: 11px; color: #94a3b8; margin-top: 3px; }
    .dept-status-badge { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 500; flex-shrink: 0; }
    .status-cleared { background: #dcfce7; color: #16a34a; }
    .status-pending { background: #fef9c3; color: #ca8a04; }
    .status-ns { background: #f1f5f9; color: #94a3b8; }
    .btn-request { background: #185FA5; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; flex-shrink: 0; }
    .btn-request:hover { background: #0C447C; }
    .btn-locked { background: #f1f5f9; color: #cbd5e1; border: none; padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: not-allowed; flex-shrink: 0; }
    .alert-box { background: #fef9c3; border: 1px solid #fde047; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #92400e; }
    .success-box { background: #dcfce7; border: 1px solid #86efac; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #166534; }
    .error-box { background: #fee2e2; border: 1px solid #fca5a5; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #991b1b; }
    .download-section { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px; }
    .download-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 14px; }
    .download-card.disabled { opacity: 0.5; cursor: not-allowed; }
    .download-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
    .download-info { flex: 1; }
    .download-title { font-size: 14px; font-weight: 600; color: #1e293b; }
    .download-sub { font-size: 11px; color: #64748b; margin-top: 2px; }
    .btn-download { background: #185FA5; color: #fff; border: none; padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; flex-shrink: 0; }
    .btn-download.locked { background: #e2e8f0; color: #94a3b8; cursor: not-allowed; }
</style>

<div class="page-title">My Clearance</div>
<div class="page-sub">Complete all departments in order to finish your clearance process.</div>

@if(session('success'))
    <div class="success-box">✅ {{ session('success') }}</div>
    @if(session('warning'))
    <div style="background:#fef9c3;border:1px solid #fde047;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#92400e;">
        ⚠️ {{ session('warning') }}
    </div>
@endif
@endif

@if(session('error'))
    <div class="error-box">❌ {{ session('error') }}</div>
@endif
{{-- DOWNLOAD SECTION --}}
<div class="download-section">
    {{-- Clearance Ticket --}}
    <div class="download-card {{ $cleared < $departments->count() ? 'disabled' : '' }}">
        <div class="download-icon" style="background:#E6F1FB">📄</div>
        <div class="download-info">
            <div class="download-title">Clearance Ticket</div>
            <div class="download-sub">
                @if($cleared < $departments->count())
                    Complete all departments to unlock
                @else
                    Digitally signed — ready to download
                @endif
            </div>
        </div>
        @if($cleared < $departments->count())
            <span class="btn-download locked">🔒 Locked</span>
        @else
            <a href="{{ route('clearance.download') }}" class="btn-download">⬇ Download</a>
        @endif
    </div>

    {{-- Financial Statement --}}
    <div class="download-card">
        <div class="download-icon" style="background:#fef9c3">💰</div>
        <div class="download-info">
            <div class="download-title">Financial Statement</div>
            <div class="download-sub">All transactions including paid and unpaid</div>
        </div>
        <a href="{{ route('financial.statement') }}" class="btn-download" style="background:#1B6B45">⬇ View</a>
    </div>
</div>

@if($penalties > 0)
<div class="alert-box">
    ⚠️ You have <strong>{{ $penalties }} outstanding payment(s)</strong>. Clear all penalties before requesting Bursary clearance.
</div>
@endif

{{-- PROGRESS --}}
<div class="card">
    <div class="card-title">Overall Progress</div>
    <div class="progress-label">
        <span>{{ $cleared }} of {{ $departments->count() }} departments cleared</span>
        <span>{{ $progress }}%</span>
    </div>
    <div class="progress-bar-bg">
        <div class="progress-bar-fill" style="width: {{ $progress }}%"></div>
    </div>
    <div class="steps">
        @foreach($departments as $dept)
            @php
                $clearance = $clearances->get($dept->id);
                $status = $clearance ? $clearance->status : 'not_started';
                $stepClass = $status === 'cleared' ? 'done' : ($status === 'pending' ? 'active' : '');
            @endphp
            <div class="step {{ $stepClass }}">
                <div class="step-dot">
                    @if($status === 'cleared') ✓
                    @else {{ $dept->order }}
                    @endif
                </div>
                <div class="step-label">{{ explode(' ', $dept->name)[0] }}</div>
            </div>
        @endforeach
    </div>
</div>

{{-- DEPARTMENT LIST --}}
<div class="card">
    <div class="card-title">Department Clearances</div>
    <div class="dept-list">
        @foreach($departments as $dept)
            @php
                $clearance   = $clearances->get($dept->id);
                $status      = $clearance ? $clearance->status : 'not_started';
                $prevDept    = $departments->where('order', $dept->order - 1)->first();
                $prevClear   = $prevDept ? $clearances->get($prevDept->id) : null;
                $isLocked    = $prevDept && (!$prevClear || $prevClear->status !== 'cleared');
                $itemClass   = $status === 'cleared' ? 'cleared'
                             : ($status === 'pending' ? 'pending'
                             : ($isLocked ? 'locked' : 'active-dept'));
            @endphp
            <div class="dept-item {{ $itemClass }}">
                <div class="dept-number">
                    @if($status === 'cleared') ✓
                    @else {{ $dept->order }}
                    @endif
                </div>
                <div class="dept-info">
                    <div class="dept-name">{{ $dept->name }}</div>
                    <div class="dept-desc">{{ $dept->description }}</div>
                    <div class="dept-date">
                        @if($status === 'cleared')
                            ✅ Cleared on {{ $clearance->cleared_at->format('d M Y') }}
                        @elseif($status === 'pending')
                            ⏳ Submitted {{ $clearance->requested_at->format('d M Y') }} — awaiting approval
                        @elseif($isLocked)
                            🔒 Complete {{ $prevDept->name }} first
                        @else
                            Not yet requested
                        @endif
                    </div>
                </div>

                @if($status === 'cleared')
                    <span class="dept-status-badge status-cleared">Cleared</span>
                @elseif($status === 'pending')
                    <span class="dept-status-badge status-pending">Pending</span>
                @elseif($isLocked)
                    <button class="btn-locked" disabled>🔒 Locked</button>
                @else
                    <form method="POST" action="{{ route('clearance.request', $dept->id) }}">
                        @csrf
                        <button type="submit" class="btn-request">Request</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</div>

@endsection