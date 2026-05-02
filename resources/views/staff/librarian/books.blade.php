<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Books — UDOM Library</title>
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
        .content { margin-left: 220px; margin-top: 56px; padding: 24px; }
        .page-title { font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .page-sub { font-size: 13px; color: #64748b; margin-bottom: 20px; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin-bottom: 16px; }
        .card-title { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px; }
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: #1B6B45; text-decoration: none; font-size: 13px; font-weight: 600; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; padding: 10px 12px; border-bottom: 1px solid #e2e8f0; }
        td { padding: 12px; font-size: 13px; color: #1e293b; border-bottom: 1px solid #f1f5f9; }
        tr:last-child td { border-bottom: none; }
        .status-badge { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 500; }
        .status-borrowed { background: #E6F1FB; color: #0C447C; }
        .status-overdue { background: #fee2e2; color: #991b1b; }
        .status-returned { background: #dcfce7; color: #16a34a; }
        .btn-return { background: #1B6B45; color: #fff; border: none; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; }
        .btn-return:hover { background: #0C3B2E; }
        .success-box { background: #dcfce7; border: 1px solid #86efac; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #166534; }
        .filter-bar { display: flex; gap: 8px; margin-bottom: 16px; }
        .filter-btn { padding: 7px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; border: 1px solid #e2e8f0; background: #fff; color: #64748b; }
        .filter-btn.active { background: #1B6B45; color: #fff; border-color: #1B6B45; }
        .empty-state { text-align: center; padding: 40px; color: #94a3b8; }
    </style>
</head>
<body>

<div class="topbar">
    <div>
        <div class="topbar-title">📚 Library Department — UDOM</div>
        <div class="topbar-sub">All Borrowed Books</div>
    </div>
    <a href="{{ route('staff.logout') }}" class="logout-btn">🚪 Logout</a>
</div>

<div class="sidebar">
    <div class="nav-section">Clearance</div>
    <a href="{{ route('staff.dashboard') }}" class="nav-item">🏠 Dashboard</a>
    <hr class="nav-divider">
    <div class="nav-section">Library</div>
    <a href="{{ route('staff.books') }}" class="nav-item active">📚 All Books</a>
    <hr class="nav-divider">
    <div class="nav-section">Account</div>
    <a href="{{ route('staff.logout') }}" class="nav-item" style="color:#e24b4a">🚪 Logout</a>
</div>

<div class="content">
    <a href="{{ route('staff.dashboard') }}" class="back-link">← Back to Dashboard</a>

    <div class="page-title">All Borrowed Books</div>
    <div class="page-sub">Track all books borrowed by students across the library.</div>

    @if(session('success'))
        <div class="success-box">✅ {{ session('success') }}</div>
    @endif

    <div class="filter-bar">
        <button class="filter-btn active" onclick="filterBooks('all', this)">All</button>
        <button class="filter-btn" onclick="filterBooks('overdue', this)">🔴 Overdue</button>
        <button class="filter-btn" onclick="filterBooks('borrowed', this)">🔵 Borrowed</button>
        <button class="filter-btn" onclick="filterBooks('returned', this)">🟢 Returned</button>
    </div>

    <div class="card">
        <div class="card-title">Book Records</div>
        @if($books->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Student</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                <tr class="book-row" data-status="{{ $book->status }}">
                    <td>
                        <div style="font-weight:600">{{ $book->book_title }}</div>
                        <div style="font-size:11px;color:#64748b">by {{ $book->author }}</div>
                        @if($book->isbn)
                            <div style="font-size:11px;color:#94a3b8">ISBN: {{ $book->isbn }}</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600">{{ $book->user->name }}</div>
                        <div style="font-size:11px;color:#64748b">{{ $book->user->reg_number }}</div>
                    </td>
                    <td>{{ $book->borrow_date->format('d M Y') }}</td>
                    <td>
                        {{ $book->due_date->format('d M Y') }}
                        @if($book->status === 'overdue')
                            <div style="font-size:11px;color:#ef4444">
                                {{ $book->due_date->diffForHumans() }}
                            </div>
                        @endif
                    </td>
                    <td>
                        {{ $book->return_date ? $book->return_date->format('d M Y') : '—' }}
                    </td>
                    <td>
                        <span class="status-badge status-{{ $book->status }}">
                            {{ ucfirst($book->status) }}
                        </span>
                    </td>
                    <td>
                        @if($book->status !== 'returned')
                            <form method="POST" action="{{ route('staff.book.return', $book->id) }}">
                                @csrf
                                <button type="submit" class="btn-return">Mark Returned</button>
                            </form>
                        @else
                            <span style="font-size:12px;color:#94a3b8">Returned</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state">📚 No books recorded yet.</div>
        @endif
    </div>
</div>

<script>
function filterBooks(status, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.book-row').forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

</body>
</html>