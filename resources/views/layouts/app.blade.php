<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clearance Management System — Brown University</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f8fafc; }

        /* TOPBAR */
        .topbar {
            background: linear-gradient(135deg, #0C447C 0%, #185FA5 60%, #378ADD 100%);
            padding: 12px 24px;
            display: flex; justify-content: space-between; align-items: center;
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 100;
            box-shadow: 0 2px 20px rgba(12,68,124,0.4);
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .hamburger { display: flex; flex-direction: column; gap: 4px; cursor: pointer; padding: 4px; }
        .hamburger span { display: block; width: 22px; height: 2px; background: #fff; border-radius: 2px; }
        .topbar-logo { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.4); }
        .topbar-title { font-size: 15px; font-weight: 700; color: #fff; }
        .topbar-sub { font-size: 11px; color: #B5D4F4; }

        {{-- TOPBAR RIGHT --}}
<div style="display:flex;align-items:center;gap:14px">

    {{-- NOTIFICATION BELL --}}
    @php
        $notifCount = \App\Models\StudentNotification::where('user_id', Auth::id())
            ->where('is_read', false)->count();
        $latestNotifs = \App\Models\StudentNotification::where('user_id', Auth::id())
            ->latest()->take(5)->get();
    @endphp

    <div style="position:relative">
        <button onclick="toggleNotifs()" style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.25);width:40px;height:40px;border-radius:50%;cursor:pointer;font-size:18px;display:flex;align-items:center;justify-content:center;position:relative;transition:all 0.2s" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
            🔔
            @if($notifCount > 0)
                <span style="position:absolute;top:-2px;right:-2px;background:#ef4444;color:#fff;font-size:10px;font-weight:700;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid #185FA5">
                    {{ $notifCount > 9 ? '9+' : $notifCount }}
                </span>
            @endif
        </button>

        {{-- DROPDOWN --}}
        <div id="notifDropdown" style="display:none;position:absolute;right:0;top:48px;width:340px;background:#fff;border-radius:16px;box-shadow:0 8px 40px rgba(0,0,0,0.2);border:1px solid #e2e8f0;z-index:200;overflow:hidden">
            <div style="padding:14px 16px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center">
                <span style="font-size:14px;font-weight:700;color:#1e293b">Notifications</span>
                @if($notifCount > 0)
                    <a href="#" onclick="markAllRead()" style="font-size:12px;color:#185FA5;text-decoration:none">Mark all read</a>
                @endif
            </div>

            @forelse($latestNotifs as $notif)
                <div style="padding:12px 16px;border-bottom:1px solid #f8fafc;display:flex;gap:12px;align-items:flex-start;background:{{ !$notif->is_read ? '#f0f7ff' : '#fff' }}">
                    <div style="width:36px;height:36px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:16px;background:{{ $notif->type === 'success' ? '#dcfce7' : ($notif->type === 'danger' ? '#fee2e2' : ($notif->type === 'warning' ? '#fef9c3' : '#E6F1FB')) }}">
                        {{ $notif->type === 'success' ? '✅' : ($notif->type === 'danger' ? '❌' : ($notif->type === 'warning' ? '⚠️' : 'ℹ️')) }}
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:600;color:#1e293b;margin-bottom:2px">{{ $notif->title }}</div>
                        <div style="font-size:12px;color:#64748b;line-height:1.4;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $notif->message }}</div>
                        <div style="font-size:11px;color:#94a3b8;margin-top:3px">{{ $notif->created_at->diffForHumans() }} • {{ $notif->from }}</div>
                    </div>
                    @if(!$notif->is_read)
                        <div style="width:8px;height:8px;border-radius:50%;background:#185FA5;flex-shrink:0;margin-top:4px"></div>
                    @endif
                </div>
            @empty
                <div style="padding:32px;text-align:center;color:#94a3b8;font-size:13px">
                    🔔 No notifications yet
                </div>
            @endforelse

            <div style="padding:12px 16px;text-align:center;border-top:1px solid #f1f5f9">
                <a href="/notifications" style="font-size:13px;color:#185FA5;text-decoration:none;font-weight:600">View all notifications →</a>
            </div>
        </div>
    </div>

    {{-- USER PROFILE --}}
    <div class="topbar-right">
        <div style="text-align:right">
            <div class="user-name">{{ Auth::user()->name }}</div>
            <div class="user-reg">{{ Auth::user()->reg_number }}</div>
        </div>
        <div class="avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->name, strrpos(Auth::user()->name, ' ') + 1, 1)) }}
        </div>
    </div>
</div>
        .topbar-right::before {
            content: '';
            position: absolute; left: 0; top: 0; bottom: 0;
            width: 40px; border-radius: 30px 0 0 30px;
            background: linear-gradient(to right, rgba(12,68,124,0.6), transparent);
        }
        .user-name { font-size: 13px; color: #fff; font-weight: 600; text-align: right; }
        .user-reg { font-size: 11px; color: #B5D4F4; }
        .avatar {
            width: 38px; height: 38px; border-radius: 50%;
            background: linear-gradient(135deg, #fff, #E6F1FB);
            display: flex; align-items: center; justify-content: center;
            color: #0C447C; font-size: 13px; font-weight: 700;
            border: 2px solid rgba(255,255,255,0.5);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed; top: 65px; left: 0; bottom: 0;
            width: 230px;
            background: linear-gradient(180deg, #0C447C 0%, #185FA5 50%, #1e6ab5 100%);
            padding: 16px 0;
            transition: transform 0.25s; z-index: 99;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(12,68,124,0.3);
        }
        .sidebar::after {
            content: '';
            position: absolute; bottom: 0; left: 0; right: 0;
            height: 120px;
            background: linear-gradient(to bottom, transparent, rgba(12,68,124,0.8));
            pointer-events: none;
        }
        .sidebar.hidden { transform: translateX(-230px); }

        /* SIDEBAR LOGO AREA */
        .sidebar-header {
            padding: 12px 18px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 8px;
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-avatar {
            width: 42px; height: 42px; border-radius: 50%;
            background: linear-gradient(135deg, #fff, #E6F1FB);
            display: flex; align-items: center; justify-content: center;
            color: #0C447C; font-size: 14px; font-weight: 700;
            border: 2px solid rgba(255,255,255,0.3);
            flex-shrink: 0;
        }
        .sidebar-user-name { font-size: 13px; font-weight: 700; color: #fff; }
        .sidebar-user-reg { font-size: 11px; color: #B5D4F4; margin-top: 2px; }

        .nav-section {
            padding: 10px 18px 4px;
            font-size: 10px; color: rgba(255,255,255,0.5);
            text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 18px; font-size: 13px; color: rgba(255,255,255,0.75);
            border-left: 3px solid transparent; text-decoration: none;
            transition: all 0.15s;
        }
        .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: rgba(255,255,255,0.4);
        }
        .nav-item.active {
            background: rgba(255,255,255,0.18);
            color: #fff;
            border-left-color: #fff;
            font-weight: 700;
        }
        .nav-badge {
            margin-left: auto; background: #ef4444;
            color: #fff; font-size: 10px;
            padding: 1px 6px; border-radius: 20px;
        }
        .nav-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 8px 0;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 230px; margin-top: 65px;
            padding: 24px; transition: margin-left 0.25s;
            min-height: calc(100vh - 65px);
        }
        .main-content.full { margin-left: 0; }

        /* CONTENT LEFT FADE */
        .main-content::before {
            content: '';
            position: fixed;
            top: 65px; left: 230px;
            width: 30px; height: calc(100vh - 65px);
            background: linear-gradient(to right, rgba(24,95,165,0.08), transparent);
            pointer-events: none;
            z-index: 10;
            transition: left 0.25s;
        }
    </style>
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar">
    <div class="topbar-left">
        <div class="hamburger" onclick="toggleSidebar()">
            <span></span><span></span><span></span>
        </div>
        <img src="/images/brown.jpg" class="topbar-logo" alt="Logo">
        <div>
            <div class="topbar-title">Clearance Management System</div>
            <div class="topbar-sub">Brown University of Technology</div>
        </div>
    </div>
    <div class="topbar-right">
        <div style="text-align:right">
            <div class="user-name">{{ Auth::user()->name }}</div>
            <div class="user-reg">{{ Auth::user()->reg_number }}</div>
        </div>
        <div class="avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->name, strrpos(Auth::user()->name, ' ') + 1, 1)) }}
        </div>
    </div>
</div>

{{-- SIDEBAR --}}
<div class="sidebar" id="sidebar">

    {{-- USER INFO IN SIDEBAR --}}
    <div class="sidebar-header">
        <div class="sidebar-avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->name, strrpos(Auth::user()->name, ' ') + 1, 1)) }}
        </div>
        <div>
            <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
            <div class="sidebar-user-reg">{{ Auth::user()->reg_number }}</div>
        </div>
    </div>

    <div class="nav-section">Main</div>
    <a href="/dashboard" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        🏠 Dashboard
    </a>
    <a href="/clearance" class="nav-item {{ request()->is('clearance') ? 'active' : '' }}">
        ✅ My Clearance
    </a>
    <hr class="nav-divider">
    <div class="nav-section">Finance</div>
    <a href="/control-numbers" class="nav-item {{ request()->is('control-numbers') ? 'active' : '' }}">
        💳 Penalties & Bills
        @php
            $unreadCount = \App\Models\Penalty::where('user_id', Auth::id())->where('status','unpaid')->count();
        @endphp
        @if($unreadCount > 0)
            <span class="nav-badge">{{ $unreadCount }}</span>
        @endif
    </a>
    <a href="/request-control-number" class="nav-item {{ request()->is('request-control-number') ? 'active' : '' }}">
        📋 Request Control No.
    </a>
    <hr class="nav-divider">
    <div class="nav-section">Documents</div>
    <a href="/clearance" class="nav-item {{ request()->is('clearance') ? 'active' : '' }}">
        📄 Clearance Ticket
    </a>
    <a href="/financial-statement" class="nav-item {{ request()->is('financial-statement') ? 'active' : '' }}">
        💰 Financial Statement
    </a>
    <hr class="nav-divider">
    <div class="nav-section">Account</div>
    <a href="/notifications" class="nav-item {{ request()->is('notifications') ? 'active' : '' }}">
    🔔 Notifications
    @php $nc = \App\Models\StudentNotification::where('user_id', Auth::id())->where('is_read', false)->count(); @endphp
    @if($nc > 0) <span class="nav-badge">{{ $nc }}</span> @endif
</a>
    <a href="/profile" class="nav-item {{ request()->is('profile') ? 'active' : '' }}">
        👤 My Profile
    </a>
    <a href="{{ route('logout') }}" class="nav-item" style="color:#fca5a5">
        🚪 Logout
    </a>
</div>

{{-- PAGE CONTENT --}}
<div class="main-content" id="mainContent">
    @yield('content')
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('hidden');
    document.getElementById('mainContent').classList.toggle('full');
}
function toggleNotifs() {
    const d = document.getElementById('notifDropdown');
    d.style.display = d.style.display === 'none' ? 'block' : 'none';
}

function markAllRead() {
    fetch('/notifications/read-all', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(() => location.reload());
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('notifDropdown');
    if (dropdown && !e.target.closest('[onclick="toggleNotifs()"]') && !dropdown.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});
</script>

</body>
</html>