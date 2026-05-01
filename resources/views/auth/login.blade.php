<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — UDOM Clearance System</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #0C447C 0%, #185FA5 50%, #378ADD 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .login-logo {
            width: 72px; height: 72px;
            background: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            font-size: 28px;
            font-weight: 800;
            color: #185FA5;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .login-title {
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .login-sub {
            color: #B5D4F4;
            font-size: 13px;
        }
        .login-card {
            background: #fff;
            border-radius: 16px;
            padding: 32px 28px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.2);
        }
        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .card-sub {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 24px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-input:focus {
            border-color: #185FA5;
            background: #fff;
            box-shadow: 0 0 0 3px #E6F1FB;
        }
        .form-input.error {
            border-color: #ef4444;
            background: #fff5f5;
        }
        .form-hint {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px;
        }
        .error-msg {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #991b1b;
            margin-bottom: 18px;
        }
        .forgot-link {
            display: block;
            text-align: right;
            font-size: 12px;
            color: #185FA5;
            text-decoration: none;
            margin-top: -10px;
            margin-bottom: 20px;
        }
        .forgot-link:hover { text-decoration: underline; }
        .btn-login {
            width: 100%;
            background: #185FA5;
            color: #fff;
            border: none;
            padding: 13px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-login:hover { background: #0C447C; }
        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #94a3b8;
        }
        .password-wrap {
            position: relative;
        }
        .toggle-pass {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 16px;
            color: #94a3b8;
            background: none;
            border: none;
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    {{-- HEADER --}}
    <div class="login-header">
        <div class="login-logo">U</div>
        <div class="login-title">UDOM Clearance System</div>
        <div class="login-sub">University of Dodoma — Student Portal</div>
    </div>

    {{-- CARD --}}
    <div class="login-card">
        <div class="card-title">Welcome back 👋</div>
        <div class="card-sub">Sign in with your registration number to continue.</div>

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="error-msg">
                ❌ {{ $errors->first() }}
            </div>
        @endif

        {{-- SUCCESS --}}
        @if (session('status'))
            <div style="background:#dcfce7;border:1px solid #86efac;border-radius:8px;padding:10px 14px;font-size:13px;color:#166534;margin-bottom:18px;">
                ✅ {{ session('status') }}
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Registration Number</label>
                <input
                    type="text"
                    name="reg_number"
                    class="form-input {{ $errors->has('reg_number') ? 'error' : '' }}"
                    placeholder="e.g. 2021-04-01234"
                    value="{{ old('reg_number') }}"
                    autocomplete="off"
                    required
                >
                <div class="form-hint">Enter your full registration number as provided by the university.</div>
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

            <a href="{{ route('forgot.password') }}" class="forgot-link">Forgot password?</a>

            <button type="submit" class="btn-login">Sign In →</button>
        </form>

        <div class="login-footer">
            Having trouble? Contact the <strong>ICT Help Desk</strong><br>
            📧 helpdesk@udom.ac.tz &nbsp;|&nbsp; 📞 +255 26 296 1000
        </div>
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