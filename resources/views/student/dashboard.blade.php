@extends('layouts.app')

@section('content')
<style>
    .profile-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
    .profile-avatar { width: 52px; height: 52px; border-radius: 50%; background: #E6F1FB; display: flex; align-items: center; justify-content: center; color: #0C447C; font-size: 16px; font-weight: 700; border: 2px solid #B5D4F4; flex-shrink: 0; }
    .profile-name { font-size: 17px; font-weight: 600; color: #1e293b; }
    .profile-meta { font-size: 12px; color: #64748b; margin-top: 4px; }
    .badge { background: #E6F1FB; color: #0C447C; font-size: 11px; padding: 2px 10px; border-radius: 20px; margin-left: 8px; font-weight: 500; }
    .stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; margin-bottom: 16px; }
    .stat { background: #fff; border-radius: 10px; padding: 14px; text-align: center; border: 1px solid #e2e8f0; }
    .stat-num { font-size: 24px; font-weight: 700; }
    .stat-label { font-size: 11px; color: #64748b; margin-top: 4px; }
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
    .dept-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .dept-card { background: #fff; border: 1px solid #e2e8f0; border-left: 4px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; }
    .dept-card.cleared { border-left-color: #22c55e; }
    .dept-card.pending { border-left-color: #f59e0b; }
    .dept-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
    .dept-name { font-size: 13px; font-weight: 600; color: #1e293b; }
    .dept-status { font-size: 10px; padding: 2px 8px; border-radius: 20px; }
    .status-cleared { background: #dcfce7; color: #16a34a; }
    .status-pending { background: #fef9c3; color: #ca8a04; }
    .status-ns { background: #f1f5f9; color: #94a3b8; }
    .dept-date { font-size: 11px; color: #94a3b8; }
    .alert-box { background: #fef9c3; border: 1px solid #fde047; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #92400e; }
</style>

{{-- ALERT if pending penalties --}}
@if($penalties > 0)
<div class="alert-box">
    ⚠️ You have <strong>{{ $penalties }} outstanding payment(s)</strong>.
    <a href="{{ route('control.numbers') }}" style="color:#92400e;font-weight:700">View Bills →</a>
</div>
@endif

{{-- PROFILE CARD --}}
<div class="profile-card">
    <div class="profile-avatar">
        {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr($user->name, strrpos($user->name, ' ') + 1, 1)) }}
    </div>
    <div>
        <div class="profile-name">{{ $user->name }} <span class="badge">2024/2025</span></div>
        <div class="profile-meta">Reg No: <strong>{{ $user->reg_number }}</strong> &nbsp;|&nbsp; {{ $user->programme }}</div>
        <div class="profile-meta">Year {{ $user->year }} &nbsp;|&nbsp; {{ $user->college }}</div>
    </div>
</div>

{{-- STATS --}}
<div class="stats">
    <div class="stat">
        <div class="stat-num" style="color:#185FA5">{{ $cleared }}</div>
        <div class="stat-label">Cleared</div>
    </div>
    <div class="stat">
        <div class="stat-num" style="color:#f59e0b">{{ $pending }}</div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat">
        <div class="stat-num" style="color:#94a3b8">{{ $notStarted }}</div>
        <div class="stat-label">Not Started</div>
    </div>
</div>

{{-- PROGRESS --}}
<div class="card">
    <div class="card-title">Overall Clearance Progress</div>
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

{{-- DEPARTMENTS --}}
<div class="card">
    <div class="card-title">Department Clearances</div>
    <div class="dept-grid">
        @foreach($departments as $index => $dept)
            @php
                $clearance = $clearances->get($dept->id);
                $status = $clearance ? $clearance->status : 'not_started';
                $cardClass = $status === 'cleared' ? 'cleared' : ($status === 'pending' ? 'pending' : '');
            @endphp
            <div class="dept-card {{ $cardClass }} {{ $index === $departments->count() - 1 ? 'style=grid-column:1/-1' : '' }}">
                <div class="dept-top">
                    <div class="dept-name">{{ $dept->name }}</div>
                    @if($status === 'cleared')
                        <span class="dept-status status-cleared">Cleared</span>
                    @elseif($status === 'pending')
                        <span class="dept-status status-pending">Pending</span>
                    @else
                        <span class="dept-status status-ns">Not Started</span>
                    @endif
                </div>
                <div class="dept-date">
                    @if($status === 'cleared')
                        ✅ Cleared on {{ $clearance->cleared_at->format('d M Y') }}
                    @elseif($status === 'pending')
                        ⏳ Submitted {{ $clearance->requested_at->format('d M Y') }}
                    @else
                        Not yet initiated
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection