<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conduct Records — Dean of Students</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f8fafc; }
        .topbar { background: linear-gradient(135deg, #0C447C 0%, #185FA5 60%, #378ADD 100%); padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 100; box-shadow: 0 2px 20px rgba(12,68,124,0.4); }
        .topbar-title { color: #fff; font-size: 16px; font-weight: 700; }
        .topbar-sub { color: #B5D4F4; font-size: 11px; }
        .logout-btn { background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3); padding: 7px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; text-decoration: none; }
        .sidebar { position: fixed; top: 65px; left: 0; bottom: 0; width: 230px; background: linear-gradient(180deg, #0C447C 0%, #185FA5 50%, #1e6ab5 100%); padding: 16px 0; z-index: 99; box-shadow: 4px 0 20px rgba(12,68,124,0.3); }
        .nav-section { padding: 10px 18px 4px; font-size: 10px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 18px; font-size: 13px; color: rgba(255,255,255,0.75); border-left: 3px solid transparent; text-decoration: none; }
        .nav-item:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.18); color: #fff; border-left-color: #fff; font-weight: 700; }
        .nav-divider { border: none; border-top: 1px solid rgba(255,255,255,0.1); margin: 8px 0; }
        .content { margin-left: 230px; margin-top: 65px; padding: 24px; }
        .page-title { font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .page-sub { font-size: 13px; color: #64748b; margin-bottom: 20px; }
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: #185FA5; text-decoration: none; font-size: 13px; font-weight: 600; margin-bottom: 16px; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px; }
        .card-title { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f1f5f9; padding: 10px 14px; font-size: 11px; color: #64748b; text-align: left; border: 1px solid #e2e8f0; text-transform: uppercase; }
        td { padding: 12px 14px; font-size: 13px; border: 1px solid #e2e8f0; color: #1e293b; }
        tr:nth-child(even) td { background: #f8fafc; }
        .severity-high { background: #fee2e2; color: #991b1b; font-size: 11px; padding: 2px 8px; border-radius: 20px; }
        .severity-medium { background: #fef9c3; color: #92400e; font-size: 11px; padding: 2px 8px; border-radius: 20px; }
        .severity-low { background: #dcfce7; color: #166534; font-size: 11px; padding: 2px 8px; border-radius: 20px; }
        .status-open { background: #fee2e2; color: #991b1b; font-size: 11px; padding: 2px 8px; border-radius: 20px; }
        .status-resolved { background: #dcfce7; color: #16a34a; font-size: 11px; padding: 2px 8px; border-radius: 20px; }
        .btn-view { background: linear-gradient(135deg, #0C447C, #185FA5); color: #fff; border: none; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; }
        .filter-bar { display: flex; gap: 8px; margin-bottom: 16px; }
        .filter-btn { padding: 7px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; border: 1px solid #e2e8f0; background: #fff; color: #64748b; }
        .filter-btn.active { background: linear-gradient(135deg, #0C447C, #185FA5); color: #fff; border-color: #185FA5; }
        .empty-state { text-align: center; padding: 40px; color: #94a3b8; }
    </style>
</head>
<body>

<div class="topbar">
    <div>
        <div class="topbar-title">🏛️ Dean of Students Office</div>
        <div class="topbar-sub">Conduct Records</div>
    </div>
    <a href="{{ route('staff.logout') }}" class="logout-btn">🚪 Logout</a>
</div>

<div class="sidebar">
    <div class="nav-section">Clearance</div>
    <a href="{{ route('dean.dashboard') }}" class="nav-item">🏠 Dashboard</a>
    <hr class="nav-divider">
    <div class="nav-section">Records</div>
    <a href="{{ route('dean.records') }}" class="nav-item active">📋 Conduct Records</a>
    <hr class="nav-divider">
    <div class="nav-section">Account</div>
    <a href="{{ route('staff.logout') }}" class="nav-item" style="color:rgba(255,100,100,0.9)">🚪 Logout</a>
</div>

<div class="content">
    <a href="{{ route('dean.dashboard') }}" class="back-link">← Back to Dashboard</a>
    <div class="page-title">All Conduct Records</div>
    <div class="page-sub">Complete record of all student conduct issues.</div>

    <div class="filter-bar">
        <button class="filter-btn active" onclick="filterRecords('all',this)">All</button>
        <button class="filter-btn" onclick="filterRecords('open',this)">🔴 Open</button>
        <button class="filter-btn" onclick="filterRecords('resolved',this)">🟢 Resolved</button>
    </div>

    <div class="card">
        @if($records->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Severity</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                <tr class="record-row" data-status="{{ $record->status }}">
                    <td>
                        <div style="font-weight:600">{{ $record->user->name }}</div>
                        <div style="font-size:11px;color:#64748b">{{ $record->user->reg_number }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600">{{ $record->title }}</div>
                        <div style="font-size:11px;color:#64748b">{{ Str::limit($record->description, 50) }}</div>
                    </td>
                    <td>{{ ucfirst($record->type) }}</td>
                    <td><span class="severity-{{ $record->severity }}">{{ ucfirst($record->severity) }}</span></td>
                    <td>{{ $record->incident_date->format('d M Y') }}</td>
                    <td><span class="status-{{ $record->status }}">{{ ucfirst($record->status) }}</span></td>
                    <td>
                        <a href="{{ route('dean.student', $record->user->id) }}" class="btn-view">View →</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state">📋 No conduct records found.</div>
        @endif
    </div>
</div>

<script>
function filterRecords(status, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.record-row').forEach(row => {
        row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
    });
}
</script>
</body>
</html>