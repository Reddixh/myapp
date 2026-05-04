<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — Brown University of Technology</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f8fafc; min-height: 100vh; display: flex; flex-direction: column; }
        .navbar { background: #fff; padding: 14px 48px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 12px rgba(0,0,0,0.08); }
        .navbar-brand { display: flex; align-items: center; gap: 12px; }
        .navbar-logo { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid #185FA5; }
        .brand-name { font-size: 15px; font-weight: 700; color: #1e293b; }
        .brand-sub { font-size: 11px; color: #64748b; }
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: #185FA5; text-decoration: none; font-size: 13px; font-weight: 600; border: 2px solid #185FA5; padding: 8px 20px; border-radius: 25px; transition: all 0.2s; }
        .back-link:hover { background: #185FA5; color: #fff; }
        .main { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 20px; background: linear-gradient(135deg, #f8fafc 60%, #E6F1FB 100%); }
        .wrapper { width: 100%; max-width: 440px; }
        .header { text-align: center; margin-bottom: 28px; }
        .logo { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #185FA5; margin: 0 auto 14px; display: block; box-shadow: 0 4px 20px rgba(24,95,165,0.2); }
        .title { color: #0C447C; font-size: 22px; font-weight: 800; margin-bottom: 4px; }
        .sub { color: #64748b; font-size: 13px; }
        .card { background: #fff; border-radius: 20px; padding: 36px 32px; box-shadow: 0 8px 40px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        .card-title { font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .card-sub { font-size: 13px; color: #64748b; margin-bottom: 28px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-input { width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 14px; color: #1e293b; background: #f8fafc; outline: none; transition: all 0.2s; }
        .form-input:focus { border-color: #185FA5; background: #fff; box-shadow: 0 0 0 3px #E6F1FB; }
        .btn { width: 100%; background: #185FA5; color: #fff; border: none; padding: 14px; border-radius: 30px; font-size: 15px; font-weight: 700; cursor: pointer; transition: all 0.2s; }
        .btn:hover { background: #0C447C; transform: translateY(-1px); }
        .success-msg { background: #dcfce7; border: 1px solid #86efac; border-radius: 10px; padding: 12px 16px; font-size: 13px; color: #166534; margin-bottom: 20px; }
        .student-link { display: block; text-align: center; margin-top: 20px; font-size: 13px; color: #185FA5; text-decoration: none; font-weight: 600; }
        .student-link:hover { text-decoration: underline; }
        .page-footer { background: #0C447C; padding: 16px 48px; display: flex; justify-content: space-between; align-items: center; }
        .page-footer-text { color: #B5D4F4; font-size: 12px; }
    </style>
</head>
<body>

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

<div class="main">
    <div class="wrapper">
        <div class="header">
            <img src="/images/brown.jpg" class="logo" alt="Logo">
            <div class="title">Reset Password</div>
            <div class="sub">Brown University of Technology</div>
        </div>

        <div class="card">
            <div class="card-title">Forgot Password 🔑</div>
            <div class="card-sub">Enter your registration number and email. We'll send you a reset link.</div>

            @if(session('status'))
                <div class="success-msg">✅ {{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('forgot.submit') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Registration Number</label>
                    <input type="text" name="reg_number" class="form-input" placeholder="e.g. 2021-04-01234" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" placeholder="e.g. j.mwamba@brown.ac.tz" required>
                </div>
                <button type="submit" class="btn">Send Reset Link →</button>
            </form>

            <a href="{{ route('login') }}" class="student-link">← Back to Login</a>
        </div>
    </div>
</div>

<footer class="page-footer">
    <div class="page-footer-text">© {{ date('Y') }} Brown University of Technology. All rights reserved.</div>
    <div class="page-footer-text">Clearance Management System</div>
</footer>

</body>
</html>