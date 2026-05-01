<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Dashboard — UDOM</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f4f8; }
        .topbar { background: #1B6B45; padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 100; }
        .topbar-title { color: #fff; font-size: 16px; font-weight: 700; }
        .topbar-sub { color: #a7f3d0; font-size: 11px; margin-top: 2px; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .staff-name { font-size: 13px; color: #a7f3d0; text-align: right; }
        .avatar { width: 36px; height: 36px; border-radius: 50%; background: #0C3B2E; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 13px; font-weight: 700; border: 2px solid #2D9B6B; }
        .logout-btn { background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3); padding: 6px 14px; border-radius: 8px; font-size: 12px; cursor: pointer; text-decoration: none; }
        .logout-btn:hover { background: rgba(255,255,255,0.25); }
        .content { margin-top: 70px; padding: 24px; max-width: 1000px; margin-left: auto; margin-right: auto; }
        .page-title { font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .page-sub { font-size: 13px; color: #64748b; margin-bottom: 20px; }
        .stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 24px; }
        .stat { background: #fff; border-radius: 12px; padding: 18px; text-align: center; border: 1px solid #e2e8f0; }
        .stat-num { font-size: 28px; font-weight: 700; }
        .stat-label { font-size: 12px; color: #64748b; margin-top: 4px; }
        .tabs { display: flex; gap: 4px; margin-bottom: 16px; background: #fff; border-radius: 10px; padding: 4px; border: 1px solid #e2e8f0; width: fit-content; }
        .tab { padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; color: #64748b; border: none; background: none; }
        .tab.active { background: #1B6B45; color: #fff; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px; }
        .card-title { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px; }
        .student-row { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .student-row:last-child { border-bottom: none; }
        .student-avatar { width: 40px; height: 40px; border-radius: 50%; background: #E6F1FB; display: flex; align-items: center; justify-content: center; color: #0C447C; font-size: 13px; font-weight: 700; flex-shrink: 0; }
        .student-info { flex: 1; }
        .student-name { font-size: 14px; font-weight: 600; color: #1e293b; }
        .student-meta { font-size: 12px; color: #64748b; margin-top: 2px; }
        .student-date { font-size: 11px; color: #94a3b8; margin-top: 2px; }
        .btn-view { background: #1B6B45; color: #fff; border: none; padding: 7px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; }
        .btn-view:hover { background: #0C3B2E; }
        .status-badge { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 500; }
        .status-pending { background: #fef9c3; color: #ca8a04; }
        .status-cleared { background: #dcfce7; color: #16a34a; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .empty-state { text-align: center; padding: 40px; color: #94a3b8; font-size: 14px; }
        .success-box { background: #dcfce7; border: 1px solid #86efac; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #166534; }
    </style>
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar">
    <div>
        <div class="topbar-title">📚 Library Department</div>
        <div class="topbar-sub">UDOM Clearance System — Staff Panel</div>
    </div>
    <div class="topbar-right">
        <div class="staff-name">
            {{ Auth::user()->name }}<br>
            <span style="font-size:10px">Librarian</span>
        </div>
        <div class="avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <a href="{{ route('staff.logout') }}" class="logout-btn">🚪 Logout</a>
    </div>
</div>

<div class="content">
    <div class="page-title">Librarian Dashboard</div>
    <div class="page-sub">Manage student clearance requests for the Library department.</div>

    @if(session('success'))
        <div class="success-box">✅ {{ session('success') }}</div>
    @endif

    {{-- STATS --}}
    <div class="stats">
        <div class="stat">
            <div class="stat-num" style="color:#f59e0b">{{ $pendingCount }}</div>
            <div class="stat-label">Pending Requests</div>
        </div>
        <div class="stat">
            <div class="stat-num" style="color:#22c55e">{{ $clearedCount }}</div>
            <div class="stat-label">Cleared Students</div>
        </div>
        <div class="stat">
            <div class="stat-num" style="color:#ef4444">{{ $rejectedCount }}</div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>

    {{-- TABS --}}
    <div class="tabs">
        <button class="tab active" onclick="showTab('pending', this)">
            ⏳ Pending ({{ $pendingCount }})
        </button>
        <button class="tab" onclick="showTab('cleared', this)">
            ✅ Cleared ({{ $clearedCount }})
        </button>
        <button class="tab" onclick="showTab('rejected', this)">
            ❌ Rejected ({{ $rejectedCount }})
        </button>
    </div>

    {{-- PENDING TAB --}}
    <div class="tab-content active" id="tab-pending">
        <div class="card">
            <div class="card-title">Pending Clearance Requests</div>
            @forelse($pending as $clearance)
                <div class="student-row">
                    <div class="student-avatar">
                        {{ strtoupper(substr($clearance->user->name, 0, 1)) }}
                    </div>
                    <div class="student-info">
                        <div class="student-name">{{ $clearance->user->name }}</div>
                        <div class="student-meta">
                            {{ $clearance->user->reg_number }} &nbsp;|&nbsp;
                            {{ $clearance->user->programme }}
                        </div>
                        <div class="student-date">
                            Requested: {{ $clearance->requested_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                    <span class="status-badge status-pending">Pending</span>
                    <a href="{{ route('staff.student', $clearance->user->id) }}" class="btn-view">
                        View →
                    </a>
                </div>
            @empty
                <div class="empty-state">🎉 No pending requests!</div>
            @endforelse
        </div>
    </div>

    {{-- CLEARED TAB --}}
    <div class="tab-content" id="tab-cleared">
        <div class="card">
            <div class="card-title">Cleared Students</div>
            @forelse($cleared as $clearance)
                <div class="student-row">
                    <div class="student-avatar" style="background:#dcfce7;color:#16a34a">
                        {{ strtoupper(substr($clearance->user->name, 0, 1)) }}
                    </div>
                    <div class="student-info">
                        <div class="student-name">{{ $clearance->user->name }}</div>
                        <div class="student-meta">
                            {{ $clearance->user->reg_number }} &nbsp;|&nbsp;
                            {{ $clearance->user->programme }}
                        </div>
                        <div class="student-date">
                            Cleared: {{ $clearance->cleared_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                    <span class="status-badge status-cleared">Cleared</span>
                    <a href="{{ route('staff.student', $clearance->user->id) }}" class="btn-view">
                        View →
                    </a>
                </div>
            @empty
                <div class="empty-state">No cleared students yet.</div>
            @endforelse
        </div>
    </div>

    {{-- REJECTED TAB --}}
    <div class="tab-content" id="tab-rejected">
        <div class="card">
            <div class="card-title">Rejected Requests</div>
            @forelse($rejected as $clearance)
                <div class="student-row">
                    <div class="student-avatar" style="background:#fee2e2;color:#991b1b">
                        {{ strtoupper(substr($clearance->user->name, 0, 1)) }}
                    </div>
                    <div class="student-info">
                        <div class="student-name">{{ $clearance->user->name }}</div>
                        <div class="student-meta">
                            {{ $clearance->user->reg_number }} &nbsp;|&nbsp;
                            {{ $clearance->user->programme }}
                        </div>
                        <div class="student-date">
                            Reason: {{ $clearance->remarks }}
                        </div>
                    </div>
                    <span class="status-badge status-rejected">Rejected</span>
                    <a href="{{ route('staff.student', $clearance->user->id) }}" class="btn-view">
                        View →
                    </a>
                </div>
            @empty
                <div class="empty-state">No rejected requests.</div>
            @endforelse
        </div>
    </div>
</div>

<script>
function showTab(name, el) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    el.classList.add('active');
}
</script>

</body>
</html>