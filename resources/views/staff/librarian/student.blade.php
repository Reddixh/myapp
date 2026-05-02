<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Detail — UDOM Library</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f4f8; }
        .topbar { background: #1B6B45; padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; position: fixed; top: 0; left: 0; right: 0; z-index: 100; }
        .topbar-title { color: #fff; font-size: 16px; font-weight: 700; }
        .topbar-sub { color: #a7f3d0; font-size: 11px; }
        .logout-btn { background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3); padding: 6px 14px; border-radius: 8px; font-size: 12px; text-decoration: none; }
        .sidebar { position: fixed; top: 56px; left: 0; bottom: 0; width: 220px; background: #fff; border-right: 1px solid #e2e8f0; padding: 12px 0; z-index: 99; }
        .nav-section { padding: 10px 16px 4px; font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.6px; font-weight: 600; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 16px; font-size: 13px; color: #64748b; border-left: 3px solid transparent; text-decoration: none; }
        .nav-item:hover { background: #f1f5f9; }
        .nav-item.active { background: #d1fae5; color: #0C3B2E; border-left-color: #1B6B45; font-weight: 600; }
        .nav-divider { border: none; border-top: 1px solid #e2e8f0; margin: 8px 0; }
        .content { margin-left: 220px; margin-top: 56px; padding: 24px; max-width: calc(100% - 220px); }
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: #1B6B45; text-decoration: none; font-size: 13px; font-weight: 600; margin-bottom: 16px; }
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
        .status-borrowed { background: #E6F1FB; color: #0C447C; }
        .status-overdue { background: #fee2e2; color: #991b1b; }
        .status-returned { background: #dcfce7; color: #16a34a; }
        .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 16px; }
        .btn-approve { background: #1B6B45; color: #fff; border: none; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; width: 100%; }
        .btn-approve:hover { background: #0C3B2E; }
        .btn-reject { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; width: 100%; }
        .form-group { margin-bottom: 14px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 5px; }
        .form-input { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #1e293b; background: #f8fafc; outline: none; }
        .form-input:focus { border-color: #1B6B45; box-shadow: 0 0 0 3px #d1fae5; }
        select.form-input { cursor: pointer; }
        .btn-submit { background: #1B6B45; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; }
        .btn-issue { background: #185FA5; color: #fff; border: none; padding: 7px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; }
        .btn-return-book { background: #f59e0b; color: #fff; border: none; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; }
        .penalty-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
        .penalty-row:last-child { border-bottom: none; }
        .book-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
        .book-row:last-child { border-bottom: none; }
        .penalty-type { font-size: 10px; padding: 2px 8px; border-radius: 20px; }
        .type-penalty { background: #fee2e2; color: #991b1b; }
        .type-outstanding_bill { background: #fef9c3; color: #92400e; }
        .ctrl-number { font-size: 12px; color: #0C447C; background: #E6F1FB; padding: 3px 10px; border-radius: 20px; }
        .success-box { background: #dcfce7; border: 1px solid #86efac; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #166534; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 200; align-items: center; justify-content: center; }
        .modal-overlay.show { display: flex; }
        .modal { background: #fff; border-radius: 16px; padding: 24px; width: 90%; max-width: 440px; }
        .modal-title { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 16px; }
        .modal-actions { display: flex; gap: 10px; margin-top: 16px; }
        .btn-cancel { background: #f1f5f9; color: #64748b; border: none; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; flex: 1; }
        .auto-clear-box { background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 10px; padding: 14px 16px; margin-bottom: 16px; font-size: 13px; color: #065f46; }
    </style>
</head>
<body>

<div class="topbar">
    <div>
        <div class="topbar-title">📚 Library Department — UDOM</div>
        <div class="topbar-sub">Student Detail</div>
    </div>
    <a href="{{ route('staff.logout') }}" class="logout-btn">🚪 Logout</a>
</div>

<div class="sidebar">
    <div class="nav-section">Clearance</div>
    <a href="{{ route('staff.dashboard') }}" class="nav-item active">🏠 Dashboard</a>
    <hr class="nav-divider">
    <div class="nav-section">Library</div>
    <a href="{{ route('staff.books') }}" class="nav-item">📚 All Books</a>
    <hr class="nav-divider">
    <div class="nav-section">Account</div>
    <a href="{{ route('staff.logout') }}" class="nav-item" style="color:#e24b4a">🚪 Logout</a>
</div>

<div class="content">
    <a href="{{ route('staff.dashboard') }}" class="back-link">← Back to Dashboard</a>

    @if(session('success'))
        <div class="success-box">✅ {{ session('success') }}</div>
    @endif

    {{-- AUTO CLEAR NOTICE --}}
    @php
        $hasBooks     = $books->where('status', 'borrowed')->count() > 0 || $books->where('status', 'overdue')->count() > 0;
        $hasPenalties = $penalties->where('status', 'unpaid')->count() > 0;
        $canAutoClear = !$hasBooks && !$hasPenalties && $clearance->status === 'pending';
    @endphp

    @if($canAutoClear)
        <div class="auto-clear-box">
            🤖 <strong>Auto-clearance eligible!</strong> This student has no borrowed books and no unpaid penalties.
            Approving will automatically clear them.
        </div>
    @endif

    <div class="grid-2">
        {{-- LEFT COLUMN --}}
        <div>
            {{-- STUDENT PROFILE --}}
            <div class="card">
                <div class="profile-header">
                    <div class="profile-avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                    <div>
                        <div class="profile-name">{{ $student->name }}</div>
                        <div class="profile-meta">{{ $student->reg_number }} | {{ $student->programme }}</div>
                        <div class="profile-meta">Year {{ $student->year }} | {{ $student->college }}</div>
                    </div>
                </div>
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
            </div>

            {{-- APPROVE / REJECT --}}
            @if($clearance->status === 'pending')
            <div class="card">
                <div class="card-title">Clearance Decision</div>
                <div class="action-grid">
                    <form method="POST" action="{{ route('staff.approve', $student->id) }}">
                        @csrf
                        <button type="submit" class="btn-approve">✅ Approve</button>
                    </form>
                    <button class="btn-reject" onclick="showRejectModal()">❌ Reject</button>
                </div>
            </div>
            @endif

            {{-- PENALTIES --}}
            <div class="card">
                <div class="card-title">Penalties & Bills</div>
                @forelse($penalties as $penalty)
                <div class="penalty-row">
                    <div style="flex:1">
                        <div style="font-size:13px;font-weight:600;color:#1e293b">{{ $penalty->name }}</div>
                        <span class="penalty-type type-{{ $penalty->type }}">
                            {{ $penalty->type === 'penalty' ? 'Penalty' : 'Outstanding Bill' }}
                        </span>
                        <div style="font-size:11px;color:#64748b;margin-top:2px">
                            Status: {{ ucfirst($penalty->status) }}
                        </div>
                    </div>
                    <div style="font-size:14px;font-weight:700;color:#ef4444">
                        TZS {{ number_format($penalty->amount) }}
                    </div>
                    @if($penalty->controlNumber && $penalty->controlNumber->control_number)
                        <span class="ctrl-number">{{ $penalty->controlNumber->control_number }}</span>
                    @else
                        <button class="btn-issue" onclick="showIssueModal({{ $penalty->id }})">
                            Issue Ctrl No.
                        </button>
                    @endif
                </div>
                @empty
                    <div style="text-align:center;padding:16px;color:#94a3b8;font-size:13px">No penalties.</div>
                @endforelse

                <div style="margin-top:16px;padding-top:14px;border-top:1px solid #f1f5f9">
                    <div class="card-title">Add Penalty</div>
                    <form method="POST" action="{{ route('staff.penalty', $student->id) }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-input" placeholder="e.g. Overdue book fine" required>
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
                                <input type="number" name="amount" class="form-input" placeholder="15000" required>
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

        {{-- RIGHT COLUMN --}}
        <div>
            {{-- BORROWED BOOKS --}}
            <div class="card">
                <div class="card-title">Borrowed Books ({{ $books->count() }})</div>
                @forelse($books as $book)
                <div class="book-row">
                    <div style="flex:1">
                        <div style="font-size:13px;font-weight:600;color:#1e293b">{{ $book->book_title }}</div>
                        <div style="font-size:11px;color:#64748b">by {{ $book->author }}</div>
                        @if($book->isbn)
                            <div style="font-size:11px;color:#94a3b8">ISBN: {{ $book->isbn }}</div>
                        @endif
                        <div style="font-size:11px;color:#94a3b8;margin-top:2px">
                            Due: {{ $book->due_date->format('d M Y') }}
                            @if($book->isOverdue())
                                <span style="color:#ef4444"> — Overdue!</span>
                            @endif
                        </div>
                    </div>
                    <span class="status-badge status-{{ $book->status }}">{{ ucfirst($book->status) }}</span>
                    @if($book->status !== 'returned')
                        <form method="POST" action="{{ route('staff.book.return', $book->id) }}">
                            @csrf
                            <button type="submit" class="btn-return-book">Return</button>
                        </form>
                    @endif
                </div>
                @empty
                    <div style="text-align:center;padding:16px;color:#94a3b8;font-size:13px">
                        No books borrowed.
                    </div>
                @endforelse

                {{-- ADD BOOK FORM --}}
                <div style="margin-top:16px;padding-top:14px;border-top:1px solid #f1f5f9">
                    <div class="card-title">Record New Borrowing</div>
                    <form method="POST" action="{{ route('staff.book.add', $student->id) }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Book Title</label>
                            <input type="text" name="book_title" class="form-input" placeholder="e.g. Introduction to Algorithms" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Author</label>
                            <input type="text" name="author" class="form-input" placeholder="e.g. Thomas H. Cormen" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">ISBN (optional)</label>
                            <input type="text" name="isbn" class="form-input" placeholder="e.g. 978-3-16-148410-0">
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                            <div class="form-group">
                                <label class="form-label">Borrow Date</label>
                                <input type="date" name="borrow_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Due Date</label>
                                <input type="date" name="due_date" class="form-input" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-submit">📚 Record Borrowing</button>
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
        <form method="POST" action="{{ route('staff.reject', $student->id) }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Reason for rejection</label>
                <textarea name="remarks" class="form-input" rows="3"
                    placeholder="e.g. Student has 3 overdue books..." required></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="hideRejectModal()">Cancel</button>
                <button type="submit" class="btn-reject" style="flex:1">Confirm Reject</button>
            </div>
        </form>
    </div>
</div>

{{-- ISSUE CONTROL NUMBER MODAL --}}
<div class="modal-overlay" id="issueModal">
    <div class="modal">
        <div class="modal-title">💳 Issue Control Number</div>
        <form method="POST" id="issueForm" action="">
            @csrf
            <div class="form-group">
                <label class="form-label">Control Number</label>
                <input type="text" name="control_number" class="form-input" placeholder="e.g. 981234567890" required>
            </div>
            <div class="form-group">
                <label class="form-label">Expiry Date</label>
                <input type="date" name="expires_at" class="form-input">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="hideIssueModal()">Cancel</button>
                <button type="submit" class="btn-issue" style="flex:1;padding:10px">Issue</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() { document.getElementById('rejectModal').classList.add('show'); }
function hideRejectModal() { document.getElementById('rejectModal').classList.remove('show'); }
function showIssueModal(id) {
    document.getElementById('issueForm').action = '/staff/control-number/' + id;
    document.getElementById('issueModal').classList.add('show');
}
function hideIssueModal() { document.getElementById('issueModal').classList.remove('show'); }
</script>

</body>
</html>