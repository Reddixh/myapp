<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Detail — Dean of Students</title>
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
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: #185FA5; text-decoration: none; font-size: 13px; font-weight: 600; margin-bottom: 16px; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 24px; margin-bottom: 16px; }
        .card-title { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px; }
        .profile-header { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
        .profile-avatar { width: 56px; height: 56px; border-radius: 50%; background: #E6F1FB; display: flex; align-items: center; justify-content: center; color: #0C447C; font-size: 18px; font-weight: 700; border: 2px solid #B5D4F4; }
        .profile-name { font-size: 18px; font-weight: 700; color: #1e293b; }
        .profile-meta { font-size: 12px; color: #64748b; margin-top: 3px; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; }
        .info-value { font-weight: 600; color: #1e293b; }
        .status-badge { font-size: 12px; padding: 4px 12px; border-radius: 20px; font-weight: 500; }
        .status-pending { background: #fef9c3; color: #ca8a04; }
        .status-cleared { background: #dcfce7; color: #16a34a; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-open { background: #fee2e2; color: #991b1b; }
        .status-resolved { background: #dcfce7; color: #16a34a; }
        .severity-high { background: #fee2e2; color: #991b1b; font-size: 11px; padding: 2px 8px; border-radius: 20px; }
        .severity-medium { background: #fef9c3; color: #92400e; font-size: 11px; padding: 2px 8px; border-radius: 20px; }
        .severity-low { background: #dcfce7; color: #166534; font-size: 11px; padding: 2px 8px; border-radius: 20px; }
        .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 16px; }
        .btn-approve { background: linear-gradient(135deg, #0C447C, #185FA5); color: #fff; border: none; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; width: 100%; }
        .btn-reject { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; width: 100%; }
        .btn-submit { background: linear-gradient(135deg, #0C447C, #185FA5); color: #fff; border: none; padding: 10px 20px; border-radius: 20px; font-size: 13px; font-weight: 700; cursor: pointer; }
        .btn-resolve { background: #dcfce7; color: #166534; border: 1px solid #86efac; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; }
        .form-group { margin-bottom: 14px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 5px; }
        .form-input { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #1e293b; background: #f8fafc; outline: none; transition: all 0.2s; }
        .form-input:focus { border-color: #185FA5; box-shadow: 0 0 0 3px #E6F1FB; }
        select.form-input { cursor: pointer; }
        .conduct-row { display: flex; align-items: flex-start; gap: 12px; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .conduct-row:last-child { border-bottom: none; }
        .conduct-info { flex: 1; }
        .conduct-title { font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 4px; }
        .conduct-desc { font-size: 12px; color: #64748b; margin-bottom: 4px; }
        .conduct-meta { font-size: 11px; color: #94a3b8; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .success-box { background: #dcfce7; border: 1px solid #86efac; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #166534; }
        .auto-clear-box { background: #dbeafe; border: 1px solid #93c5fd; border-radius: 10px; padding: 14px 16px; margin-bottom: 16px; font-size: 13px; color: #1e40af; }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 200; align-items: center; justify-content: center; }
        .modal-overlay.show { display: flex; }
        .modal { background: #fff; border-radius: 16px; padding: 24px; width: 90%; max-width: 440px; }
        .modal-title { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 16px; }
        .modal-actions { display: flex; gap: 10px; margin-top: 16px; }
        .btn-cancel { background: #f1f5f9; color: #64748b; border: none; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; flex: 1; }
    </style>
</head>
<body>

<div class="topbar">
    <div>
        <div class="topbar-title">🏛️ Dean of Students Office</div>
        <div class="topbar-sub">Student Detail</div>
    </div>
    <a href="{{ route('staff.logout') }}" class="logout-btn">🚪 Logout</a>
</div>

<div class="sidebar">
    <div class="nav-section">Clearance</div>
    <a href="{{ route('dean.dashboard') }}" class="nav-item active">🏠 Dashboard</a>
    <hr class="nav-divider">
    <div class="nav-section">Records</div>
    <a href="{{ route('dean.records') }}" class="nav-item">📋 Conduct Records</a>
    <hr class="nav-divider">
    <div class="nav-section">Account</div>
    <a href="{{ route('staff.logout') }}" class="nav-item" style="color:rgba(255,100,100,0.9)">🚪 Logout</a>
</div>

<div class="content">
    <a href="{{ route('dean.dashboard') }}" class="back-link">← Back to Dashboard</a>

    @if(session('success'))
        <div class="success-box">✅ {{ session('success') }}</div>
    @endif

    @php
        $hasOpenRecords = $conductRecords->where('status','open')->count() > 0;
        $hasPenalties   = $penalties->where('status','unpaid')->count() > 0;
        $canAutoClear   = !$hasOpenRecords && !$hasPenalties && $clearance && $clearance->status === 'pending';
    @endphp

    @if($canAutoClear)
        <div class="auto-clear-box">
            🤖 <strong>Auto-clearance eligible!</strong> This student has no open conduct records and no unpaid penalties.
        </div>
    @endif

    <div class="grid-2">
        {{-- LEFT --}}
        <div>
            <div class="card">
                <div class="profile-header">
                    <div class="profile-avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                    <div>
                        <div class="profile-name">{{ $student->name }}</div>
                        <div class="profile-meta">{{ $student->reg_number }} | {{ $student->programme }}</div>
                        <div class="profile-meta">Year {{ $student->year }} | {{ $student->college }}</div>
                    </div>
                </div>
                @if($clearance)
                <div class="info-row">
                    <span class="info-label">Clearance Status</span>
                    <span class="status-badge status-{{ $clearance->status }}">{{ ucfirst($clearance->status) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Requested On</span>
                    <span class="info-value">{{ $clearance->requested_at->format('d M Y, h:i A') }}</span>
                </div>
                @if($clearance->cleared_at)
                <div class="info-row">
                    <span class="info-label">Cleared On</span>
                    <span class="info-value">{{ $clearance->cleared_at->format('d M Y, h:i A') }}</span>
                </div>
                @endif
                @if($clearance->remarks)
                <div class="info-row">
                    <span class="info-label">Remarks</span>
                    <span class="info-value">{{ $clearance->remarks }}</span>
                </div>
                @endif
                @endif
            </div>

            @if($clearance && $clearance->status === 'pending')
            <div class="card">
                <div class="card-title">Clearance Decision</div>
                <div class="action-grid">
                    <form method="POST" action="{{ route('dean.approve', $student->id) }}">
                        @csrf
                        <button type="submit" class="btn-approve">✅ Approve</button>
                    </form>
                    <button class="btn-reject" onclick="showRejectModal()">❌ Reject</button>
                </div>
            </div>
            @endif

            {{-- PENALTIES --}}
            <div class="card">
                <div class="card-title">Penalties & Bills ({{ $penalties->count() }})</div>
                @forelse($penalties as $penalty)
                <div class="info-row">
                    <div>
                        <div style="font-size:13px;font-weight:600;color:#1e293b">{{ $penalty->name }}</div>
                        <div style="font-size:11px;color:#64748b">{{ ucfirst(str_replace('_',' ',$penalty->type)) }} — {{ ucfirst($penalty->status) }}</div>
                    </div>
                    <div style="font-size:14px;font-weight:700;color:#ef4444">TZS {{ number_format($penalty->amount) }}</div>
                </div>
                @empty
                    <div style="text-align:center;padding:16px;color:#94a3b8;font-size:13px">No penalties.</div>
                @endforelse

                <div style="margin-top:16px;padding-top:14px;border-top:1px solid #f1f5f9">
                    <div class="card-title">Add Penalty</div>
                    <form method="POST" action="{{ route('dean.penalty', $student->id) }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-input" placeholder="e.g. Misconduct fine" required>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                            <div class="form-group">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-input">
                                    <option value="penalty">Penalty</option>
                                    <option value="outstanding_bill">Outstanding Bill</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Amount (TZS)</label>
                                <input type="number" name="amount" class="form-input" placeholder="50000" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-input">
                        </div>
                        <button type="submit" class="btn-submit">Add Penalty</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div>
            <div class="card">
                <div class="card-title">Conduct Records ({{ $conductRecords->count() }})</div>
                @forelse($conductRecords as $record)
                <div class="conduct-row">
                    <div class="conduct-info">
                        <div class="conduct-title">{{ $record->title }}</div>
                        <div class="conduct-desc">{{ $record->description }}</div>
                        <div class="conduct-meta">
                            Type: {{ ucfirst($record->type) }} &nbsp;|&nbsp;
                            Date: {{ $record->incident_date->format('d M Y') }}
                            @if($record->resolved_date)
                                &nbsp;|&nbsp; Resolved: {{ $record->resolved_date->format('d M Y') }}
                            @endif
                        </div>
                        <div style="margin-top:6px;display:flex;gap:8px;align-items:center">
                            <span class="severity-{{ $record->severity }}">{{ ucfirst($record->severity) }} severity</span>
                            <span class="status-badge status-{{ $record->status }}" style="font-size:11px;padding:2px 8px">{{ ucfirst($record->status) }}</span>
                        </div>
                    </div>
                    @if($record->status === 'open')
                        <form method="POST" action="{{ route('dean.resolve', $record->id) }}">
                            @csrf
                            <button type="submit" class="btn-resolve">✓ Resolve</button>
                        </form>
                    @endif
                </div>
                @empty
                    <div style="text-align:center;padding:16px;color:#94a3b8;font-size:13px">No conduct records.</div>
                @endforelse

                <div style="margin-top:16px;padding-top:14px;border-top:1px solid #f1f5f9">
                    <div class="card-title">Add Conduct Record</div>
                    <form method="POST" action="{{ route('dean.conduct', $student->id) }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-input" placeholder="e.g. Exam misconduct" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-input" rows="2" placeholder="Describe the incident..." required></textarea>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                            <div class="form-group">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-input">
                                    <option value="misconduct">Misconduct</option>
                                    <option value="academic">Academic</option>
                                    <option value="financial">Financial</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Severity</label>
                                <select name="severity" class="form-input">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Incident Date</label>
                            <input type="date" name="incident_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="btn-submit">📋 Add Record</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- REJECT MODAL --}}
<div class="modal-overlay" id="rejectModal">
    <div class="modal">
        <div class="modal-title">❌ Reject Clearance</div>
        <form method="POST" action="{{ route('dean.reject', $student->id) }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Reason for rejection</label>
                <textarea name="remarks" class="form-input" rows="3" placeholder="e.g. Student has open conduct records..." required></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="hideRejectModal()">Cancel</button>
                <button type="submit" class="btn-reject" style="flex:1">Confirm Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() { document.getElementById('rejectModal').classList.add('show'); }
function hideRejectModal() { document.getElementById('rejectModal').classList.remove('show'); }
</script>

</body>
</html>