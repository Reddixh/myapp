<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login — UDOM Clearance System</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #0C3B2E 0%, #1B6B45 50%, #2D9B6B 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .wrapper { width: 100%; max-width: 420px; }
        .header { text-align: center; margin-bottom: 28px; }
        .logo { width: 72px; height: 72px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; font-size: 28px; font-weight: 800; color: #1B6B45; box-shadow: 0 4px 20px rgba(0,0,0,0.2); }
        .title { color: #fff; font-size: 22px; font-weight: 700; margin-bottom: 4px; }
        .sub { color: #a7f3d0; font-size: 13px; }
        .staff-badge { display: inline-block; background: rgba(255,255,255,0.2); color: #fff; font-size: 11px; padding: 4px 14px; border-radius: 20px; margin-top: 8px; border: 1px solid rgba(255,255,255,0.3); }
        .card { background: #fff; border-radius: 16px; padding: 32px 28px; box-shadow: 0 8px 40px rgba(0,0,0,0.2); }
        .card-title { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .card-sub { font-size: 13px; color: #64748b; margin-bottom: 24px; }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-input { width: 100%; padding: 11px 14px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 14px; color: #1e293b; background: #f8fafc; outline: none; transition: border-color 0.2s; }
        .form-input:focus { border-color: #1B6B45; background: #fff; box-shadow: 0 0 0 3px #d1fae5; }
        .form-input.error { border-color: #ef4444; }
        .error-msg { background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #991b1b; margin-bottom: 18px; }
        .btn-login { width: 100%; background: #1B6B45; color: #fff; border: none; padding: 13px; border-radius: 8px; font-size: 15px; font-weight: 700; cursor: pointer; }
        .btn-login:hover { background: #0C3B2E; }
        .password-wrap { position: relative; }
        .toggle-pass { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 16px; color: #94a3b8; background: none; border: none; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #a7f3d0; }
        .student-link { display: block; text-align: center; margin-top: 16px; font-size: 13px; color: #1B6B45; text-decoration: none; }
        .student-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="header">
        <div class="logo">U</div>
        <div class="title">UDOM Clearance System</div>
        <div class="sub">University of Dodoma</div>
        <span class="staff-badge">🏢 Staff Portal</span>
    </div>

    <div class="card">
        <div class="card-title">Staff Login 👨‍💼</div>
        <div class="card-sub">Sign in with your staff email and password.</div>

        @if($errors->any())
            <div class="error-msg">❌ {{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('staff.login.submit') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input
                    type="email"
                    name="email"
                    class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                    placeholder="e.g. m.kileo@udom.ac.tz"
                    value="{{ old('email') }}"
                    required
                >
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="password-wrap">
                    <input
                        type="password"
                        name="password"
                        id="passwordInput"
                        class="form-input"
                        placeholder="Enter your password"
                        required
                    >
                    <button type="button" class="toggle-pass" onclick="togglePassword()">👁️</button>
                </div>
            </div>
            <button type="submit" class="btn-login">Sign In →</button>
        </form>

        <a href="{{ route('login') }}" class="student-link">← Student Portal</a>
    </div>

    <div class="footer">
        Having trouble? Contact ICT Help Desk &nbsp;|&nbsp; helpdesk@udom.ac.tz
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>