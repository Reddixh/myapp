<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UDOM Clearance System</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f4f8; }
        .topbar {
            background: #185FA5;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .hamburger { display: flex; flex-direction: column; gap: 4px; cursor: pointer; }
        .hamburger span { display: block; width: 22px; height: 2px; background: #fff; border-radius: 2px; }
        .topbar-title { color: #fff; font-size: 16px; font-weight: 600; }
        .topbar-sub { color: #B5D4F4; font-size: 11px; }
        .avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: #0C447C; display: flex; align-items: center;
            justify-content: center; color: #fff; font-size: 13px;
            font-weight: 600; border: 2px solid #378ADD;
        }
        .sidebar {
            position: fixed;
            top: 56px; left: 0; bottom: 0;
            width: 220px;
            background: #fff;
            border-right: 1px solid #e2e8f0;
            padding: 12px 0;
            transition: transform 0.25s;
            z-index: 99;
            overflow-y: auto;
        }
        .sidebar.hidden { transform: translateX(-220px); }
        .nav-section {
            padding: 10px 16px 4px;
            font-size: 10px; color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.6px; font-weight: 600;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 16px; font-size: 13px; color: #64748b;
            border-left: 3px solid transparent;
            text-decoration: none;
        }
        .nav-item:hover { background: #f1f5f9; color: #1e293b; }
        .nav-item.active {
            background: #E6F1FB; color: #0C447C;
            border-left-color: #185FA5; font-weight: 600;
        }
        .nav-badge {
            margin-left: auto; background: #E24B4A;
            color: #fff; font-size: 10px;
            padding: 1px 6px; border-radius: 20px;
        }
        .nav-divider { border: none; border-top: 1px solid #e2e8f0; margin: 8px 0; }
        .main-content {
            margin-left: 220px;
            margin-top: 56px;
            padding: 24px;
            transition: margin-left 0.25s;
            min-height: calc(100vh - 56px);
        }
        .main-content.full { margin-left: 0; }
    </style>
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar">
    <div class="topbar-left">
        <div class="hamburger" onclick="toggleSidebar()">
            <span></span><span></span><span></span>
        </div>
        <div>
            <div class="topbar-title">UDOM Clearance System</div>
            <div class="topbar-sub">University of Dodoma</div>
        </div>
    </div>
    <div style="display:flex;align-items:center;gap:10px">
        <div style="font-size:12px;color:#B5D4F4;text-align:right">
    {{ Auth::user()->name }}<br>
    <span style="font-size:10px">{{ Auth::user()->reg_number }}</span>
</div>
<div class="avatar">
    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->name, strrpos(Auth::user()->name, ' ') + 1, 1)) }}
</div>
    </div>
</div>

{{-- SIDEBAR --}}
<div class="sidebar" id="sidebar">
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
        💳 Bills and Pelnaties
        <span class="nav-badge">2</span>
    </a>
    <a href="/request-control-number" class="nav-item {{ request()->is('request-control-number') ? 'active' : '' }}">
        📋 Request Control No.
    </a>
    <hr class="nav-divider">
    <div class="nav-section">Account</div>
    <a href="/profile" class="nav-item {{ request()->is('profile') ? 'active' : '' }}">
        👤 My Profile
    </a>
    <a href="{{ route('logout') }}" class="nav-item" style="color:#e24b4a">
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
</script>

</body>
</html>