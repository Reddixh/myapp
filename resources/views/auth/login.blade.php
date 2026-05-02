<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login — Brown University of Technology</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* NAVBAR */
        .navbar {
            background: #fff;
            padding: 14px 48px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 1px 12px rgba(0,0,0,0.08);
        }
        .navbar-brand { display: flex; align-items: center; gap: 12px; }
        .navbar-logo {
            width: 44px; height: 44px; border-radius: 50%;
            object-fit: cover; border: 2px solid #185FA5;
        }
        .brand-name { font-size: 15px; font-weight: 700; color: #1e293b; }
        .brand-sub { font-size: 11px; color: #64748b; }
        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            color: #185FA5; text-decoration: none;
            font-size: 13px; font-weight: 600;
            border: 2px solid #185FA5;
            padding: 8px 20px; border-radius: 25px;
            transition: all 0.2s;
        }
        .back-link:hover { background: #185FA5; color: #fff; }

        /* MAIN */
        .main {
            flex: 1;
            display: flex; align-items: center; justify-content: center;
            padding: 40px 20px;
            background: linear-gradient(135deg, #f8fafc 60%, #E6F1FB 100%);
        }
        .login-wrapper { width: 100%; max-width: 440px; }

        /* HEADER */
        .login-header { text-align: center; margin-bottom: 28px; }
        .login-logo {
            width: 80px; height: 80px; border-radius: 50%;
            object-fit: cover;
            border: 3px solid #185FA5;
            margin: 0 auto 14px;
            display: block;
            box-shadow: 0 4px 20px rgba(24,95,165,0.2);
        }
        .login-logo-placeholder {
            width: 80px; height: 80px; border-radius: 50%;
            background: linear-gradient(135deg, #0C447C, #185FA5);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            font-size: 28px; font-weight: 800; color: #fff;
            box-shadow: 0 4px 20px rgba(24,95,165,0.3);
        }
        .login-title { color: #0C447C; font-size: 22px; font-weight: 800; margin-bottom: 4px; }
        .login-sub { color: #64748b; font-size: 13px; }

        /* CARD */
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
        }
        .card-title { font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .card-sub { font-size: 13px; color: #64748b; margin-bottom: 28px; }

        /* FORM */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-input {
            width: 100%; padding: 12px 16px;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 14px; color: #1e293b; background: #f8fafc;
            outline: none; transition: all 0.2s;
        }
        .form-input:focus {
            border-color: #185FA5; background: #fff;
            box-shadow: 0 0 0 3px #E6F1FB;
        }
        .form-input.error { border-color: #ef4444; background: #fff5f5; }
        .form-hint { font-size: 11px; color: #94a3b8; margin-top: 4px; }
        .password-wrap { position: relative; }
        .toggle-pass {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            cursor: pointer; font-size: 16px; color: #94a3b8;
            background: none; border: none;
        }

        /* FORGOT */
        .forgot-link {
            display: block; text-align: right;
            font-size: 12px; color: #185FA5;
            text-decoration: none;
            margin-top: -12px; margin-bottom: 22px;
        }
        .forgot-link:hover { text-decoration: underline; }

        /* BUTTON */
        .btn-login {
            width: 100%; background: #185FA5; color: #fff;
            border: none; padding: 14px;
            border-radius: 30px; font-size: 15px; font-weight: 700;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-login:hover { background: #0C447C; transform: translateY(-1px); }

        /* ERROR */
        .error-msg {
            background: #fee2e2; border: 1px solid #fca5a5;
            border-radius: 10px; padding: 12px 16px;
            font-size: 13px; color: #991b1b; margin-bottom: 20px;
        }

        /* SUCCESS */
        .success-msg {
            background: #dcfce7; border: 1px solid #86efac;
            border-radius: 10px; padding: 12px 16px;
            font-size: 13px; color: #166534; margin-bottom: 20px;
        }

        /* FOOTER */
        .login-footer {
            text-align: center; margin-top: 20px;
            font-size: 12px; color: #94a3b8; line-height: 1.8;
        }
        .login-footer a { color: #185FA5; text-decoration: none; }

        /* PAGE FOOTER */
        .page-footer {
            background: #0C447C; padding: 16px 48px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .page-footer-text { color: #B5D4F4; font-size: 12px; }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar">
    <div class="navbar-brand">
        <img src="/images/brown.jpg" class="navbar-logo" alt="Logo">
        <div>
            <div class="brand-name">Clearance Management System</div>
            <div class="brand-sub">Brown University of Technology</div>
        </div>
    </div>
    <a href="{{ route('home') }}" class="back-link">← Back to Home</a>
</nav>

{{-- MAIN --}}
<div class="main">
    <div class="login-wrapper">

        <div class="login-header">
            <img src="/images/brown.jpg" class="login-logo" alt="Logo">
            <div class="login-title">Student Portal</div>
            <div class="login-sub">Brown University of Technology</div>
        </div>

        <div class="login-card">
            <div class="card-title">Welcome back 👋</div>
            <div class="card-sub">Sign in with your registration number to continue.</div>

            @if($errors->any())
                <div class="error-msg">❌ {{ $errors->first() }}</div>
            @endif

            @if(session('status'))
                <div class="success-msg">✅ {{ session('status') }}</div>
            @endif

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
                    <div class="form-hint">Enter your full registration number as issued by the university.</div>
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
                <a href="mailto:helpdesk@brown.ac.tz">helpdesk@brown.ac.tz</a>
                &nbsp;|&nbsp;
                <a href="tel:+255747476275">+255 74 747 6275</a>
            </div>
        </div>
    </div>
</div>

{{-- PAGE FOOTER --}}
<footer class="page-footer">
    <div class="page-footer-text">© {{ date('Y') }} Brown University of Technology. All rights reserved.</div>
    <div class="page-footer-text">Clearance Management System</div>
</footer>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>