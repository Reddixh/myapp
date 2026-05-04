<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Department Dashboard — Brown University</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f8fafc; }
        .topbar { background: linear-gradient(135deg, #0C447C 0%, #185FA5 60%, #378ADD 100%); padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 100; box-shadow: 0 2px 20px rgba(12,68,124,0.4); }
        .topbar-title { color: #fff; font-size: 16px; font-weight: 700; }
        .topbar-sub { color: #B5D4F4; font-size: 11px; margin-top: 2px; }
        .topbar-right { display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.2); }
        .staff-name { font-size: 13px; color: #fff; font-weight: 600; text-align: right; }
        .staff-sub { font-size: 11px; color: #B5D4F4; }
        .avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #fff, #E6F1FB); display: flex; align-items: center; justify-content: center; color: #0C447C; font-size: 13px; font-weight: 700; border: 2px solid rgba(255,255,255,0.4); }
        .logout-btn { background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3); padding: 7px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; text-decoration: none; }
        .sidebar { position: fixed; top: 65px; left: 0; bottom: 0; width: 230px; background: linear-gradient(180deg, #0C447C 0%, #185FA5 50%, #1e6ab5 100%); padding: 16px 0; z-index: 99; overflow-y: auto; box-shadow: 4px 0 20px rgba(12,68,124,0.3); }
        .nav-section { padding: 10px 18px 4px; font-size: 10px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 18px; font-size: 13px; color: rgba(255,255,255,0.75); border-left: 3px solid transparent; text-decoration: none; transition: all 0.15s; }
        .nav-item:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.18); color: #fff; border-left-color: #fff; font-weight: 700; }
        .nav-divider { border: none; border-top: 1px solid rgba(255,255,255,0.1); margin: 8px 0; }
        .content { margin-left: 230px; margin-top: 65px; padding: 24px; }
        .page-title { font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .page-sub { font-size: 13px; color: #64748b; margin-bottom: 20px; }
        .stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 24px; }
        .stat { background: #fff; border-radius: 12px; padding: 18px; text-align: center; border: 1px solid #e2e8f0; }
        .stat-num { font-size: 28px; font-weight: 700; }
        .stat-label { font-size: 12px; color: #64748b; margin-top: 4px; }
        .tabs { display: flex; gap: 4px; margin-bottom: 16px; background: #fff; border-radius: 10px; padding: 4px; border: 1px solid #e2e8f0; width: fit-content; }
        .tab { padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; color: #64748b; border: none; background: none; }
        .tab.active { background: linear-gradient(135deg, #0C447C, #185FA5); color: #fff; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px; }
        .card-title { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px; }
        .student-row { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .student-row:last-child { border-bottom: none; }
        .student-avatar { width: 42px; height: 42px; border-radius: 50%; background: #E6F1FB; display: flex; align-items: center; justify-content: center; color: #0C447C; font-size: 13px; font-weight: 700; flex-shrink: 0; }
        .student-info { flex: 1; }
        .student-name { font-size: 14px; font-weight: 600; color: #1e293b; }
        .student-meta { font-size: 12px; color: #64748b; margin-top: 2px; }
        .student-date { font-size: 11px; color: #94a3b8; margin-top: 2px; }
        .btn-view { background: linear-gradient(135deg, #0C447C, #185FA5); color: #fff; border: none; padding: 7px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; }
        .status-badge { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 500; }
        .status-pending { background: #fef9c3; color: #ca8a04; }
        .status-cleared { background: #dcfce7; color: #16a34a; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .empty-state { text-align: center; padding: 40px; color: #94a3b8; font-size: 14px; }
        .success-box { background: #dcfce7; border: 1px solid #86efac; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #166634; }
    </style>
</head>
<body>

<div class="topbar">
    <div>
        <div class="topbar-title">💻 IT Department</div>
        <div class="topbar-sub">Brown University — Staff Panel</div>
    </div>
    <div style="display:flex;align-items:center;gap:12px">
        <div class="topbar-right">
            <div>
                <div class="staff-name">{{ Auth::user()->name }}</div>
                <div class="staff-sub">IT Officer</div>
            </div>
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
        </div>
        <a href="{{ route('staff.logout') }}" class="logout-btn">🚪 Logout</a>
    </div>
</div>

<div class="sidebar">
    <div class="nav-section">Clearance</div>
    <a href="{{ route('it.dashboard') }}" class="nav-item active">🏠 Dashboard</a>
    <hr class="nav-divider">
    <div class="nav-section">Equipment</div>
    <a href="{{ route('it.equipment') }}" class="nav-item">💻 All Equipment</a>
    <hr class="nav-divider">
    <div class="nav-section">Account</div>
    <a href="{{ route('staff.logout') }}" class="nav-item" style="color:rgba(255,100,100,0.9)">🚪 Logout</a>
</div>

<div class="content">
    <div class="page-title">IT Department Dashboard</div>
    <div class="page-sub">Manage student clearance requests and IT equipment.</div>

    @if(session('success'))
        <div class="success-box">✅ {{ session('success') }}</div>
    @endif

    <div class="stats">
        <div class="stat">
            <div class="stat-num" style="color:#f59e0b">{{ $pending->count() }}</div>
            <div class="stat-label">Pending Requests</div>
        </div>
        <div class="stat">
            <div class="stat-num" style="color:#22c55e">{{ $cleared->count() }}</div>
            <div class="stat-label">Cleared Students</div>
        </div>
        <div class="stat">
            <div class="stat-num" style="color:#185FA5">{{ $issuedEquipment }}</div>
            <div class="stat-label">Issued Equipment</div>
        </div>
        <div class="stat">
            <div class="stat-num" style="color:#ef4444">{{ $lostEquipment }}</div>
            <div class="stat-label">Lost Equipment</div>
        </div>
    </div>

    <div class="tabs">
        <button class="tab active" onclick="showTab('pending',this)">⏳ Pending ({{ $pending->count() }})</button>
        <button class="tab" onclick="showTab('cleared',this)">✅ Cleared ({{ $cleared->count() }})</button>
        <button class="tab" onclick="showTab('rejected',this)">❌ Rejected ({{ $rejected->count() }})</button>
    </div>

    <div class="tab-content active" id="tab-pending">
        <div class="card">
            <div class="card-title">Pending Clearance Requests</div>
            @forelse($pending as $clearance)
            <div class="student-row">
                <div class="student-avatar">{{ strtoupper(substr($clearance->user->name, 0, 1)) }}</div>
                <div class="student-info">
                    <div class="student-name">{{ $clearance->user->name }}</div>
                    <div class="student-meta">{{ $clearance->user->reg_number }} | {{ $clearance->user->programme }}</div>
                    <div class="student-date">Requested: {{ $clearance->requested_at->format('d M Y, h:i A') }}</div>
                </div>
                <span class="status-badge status-pending">Pending</span>
                <a href="{{ route('it.student', $clearance->user->id) }}" class="btn-view">View →</a>
            </div>
            @empty
                <div class="empty-state">🎉 No pending requests!</div>
            @endforelse
        </div>
    </div>

    <div class="tab-content" id="tab-cleared">
        <div class="card">
            <div class="card-title">Cleared Students</div>
            @forelse($cleared as $clearance)
            <div class="student-row">
                <div class="student-avatar" style="background:#dcfce7;color:#16a34a">{{ strtoupper(substr($clearance->user->name, 0, 1)) }}</div>
                <div class="student-info">
                    <div class="student-name">{{ $clearance->user->name }}</div>
                    <div class="student-meta">{{ $clearance->user->reg_number }} | {{ $clearance->user->programme }}</div>
                    <div class="student-date">Cleared: {{ $clearance->cleared_at->format('d M Y, h:i A') }}</div>
                </div>
                <span class="status-badge status-cleared">Cleared</span>
                <a href="{{ route('it.student', $clearance->user->id) }}" class="btn-view">View →</a>
            </div>
            @empty
                <div class="empty-state">No cleared students yet.</div>
            @endforelse
        </div>
    </div>

    <div class="tab-content" id="tab-rejected">
        <div class="card">
            <div class="card-title">Rejected Requests</div>
            @forelse($rejected as $clearance)
            <div class="student-row">
                <div class="student-avatar" style="background:#fee2e2;color:#991b1b">{{ strtoupper(substr($clearance->user->name, 0, 1)) }}</div>
                <div class="student-info">
                    <div class="student-name">{{ $clearance->user->name }}</div>
                    <div class="student-meta">{{ $clearance->user->reg_number }} | {{ $clearance->user->programme }}</div>
                    <div class="student-date">Reason: {{ $clearance->remarks }}</div>
                </div>
                <span class="status-badge status-rejected">Rejected</span>
                <a href="{{ route('it.student', $clearance->user->id) }}" class="btn-view">View →</a>
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