<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UDOM Student Clearance System</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f8fafc; color: #1e293b; }

        /* NAVBAR */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0;
            background: #fff;
            padding: 14px 48px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 1px 12px rgba(0,0,0,0.08);
            z-index: 100;
        }
        .navbar-brand { display: flex; align-items: center; gap: 12px; }
        .navbar-logo {
            width: 48px; height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #185FA5;
        }
        .navbar-logo-placeholder {
            width: 48px; height: 48px; border-radius: 50%;
            background: linear-gradient(135deg, #0C447C, #185FA5);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 16px; font-weight: 700;
            border: 2px solid #185FA5;
        }
        .brand-name { font-size: 15px; font-weight: 700; color: #1e293b; }
        .brand-sub { font-size: 11px; color: #64748b; }
        .navbar-links { display: flex; align-items: center; gap: 24px; }
        .nav-link { font-size: 13px; color: #64748b; text-decoration: none; font-weight: 500; }
        .nav-link:hover { color: #185FA5; }
        .navbar-btns { display: flex; gap: 10px; }
        .btn-staff {
            background: #fff; color: #185FA5;
            border: 2px solid #185FA5;
            padding: 8px 20px; border-radius: 25px;
            font-size: 13px; font-weight: 700;
            text-decoration: none; cursor: pointer;
            transition: all 0.2s;
        }
        .btn-staff:hover { background: #185FA5; color: #fff; }
        .btn-student {
            background: #185FA5; color: #fff;
            border: 2px solid #185FA5;
            padding: 8px 20px; border-radius: 25px;
            font-size: 13px; font-weight: 700;
            text-decoration: none; cursor: pointer;
            transition: all 0.2s;
        }
        .btn-student:hover { background: #0C447C; border-color: #0C447C; }

        /* HERO */
        .hero {
            min-height: 100vh;
            display: flex; align-items: center;
            padding: 100px 48px 60px;
            background: linear-gradient(135deg, #f8fafc 60%, #E6F1FB 100%);
        }
        .hero-content { flex: 1; max-width: 520px; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: #E6F1FB; color: #185FA5;
            font-size: 12px; font-weight: 600;
            padding: 6px 14px; border-radius: 20px;
            margin-bottom: 20px;
        }
        .hero-title {
            font-size: 48px; font-weight: 800;
            color: #0C447C; line-height: 1.15;
            margin-bottom: 16px;
        }
        .hero-title span { color: #185FA5; }
        .hero-desc {
            font-size: 16px; color: #64748b;
            line-height: 1.7; margin-bottom: 32px;
        }
        .hero-btns { display: flex; gap: 14px; flex-wrap: wrap; }
        .btn-hero-primary {
            background: #185FA5; color: #fff;
            padding: 14px 32px; border-radius: 30px;
            font-size: 15px; font-weight: 700;
            text-decoration: none; border: none; cursor: pointer;
            transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-hero-primary:hover { background: #0C447C; transform: translateY(-1px); }
        .btn-hero-secondary {
            background: #fff; color: #185FA5;
            padding: 14px 32px; border-radius: 30px;
            font-size: 15px; font-weight: 700;
            text-decoration: none; border: 2px solid #185FA5; cursor: pointer;
            transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-hero-secondary:hover { background: #E6F1FB; }
        .hero-image {
            flex: 1; display: flex; justify-content: center; align-items: center;
            padding-left: 40px;
        }
        .hero-img-box {
            width: 460px; height: 380px;
            border-radius: 20px;
            background: linear-gradient(135deg, #0C447C, #185FA5, #378ADD);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            box-shadow: 0 20px 60px rgba(24,95,165,0.3);
            position: relative; overflow: hidden;
            padding: 32px;
        }
        .hero-img-box::before {
            content: '';
            position: absolute; top: -40px; right: -40px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }
        .hero-img-box::after {
            content: '';
            position: absolute; bottom: -30px; left: -30px;
            width: 150px; height: 150px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .hero-logo-big {
            width: 100px; height: 100px; border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255,255,255,0.3);
            margin-bottom: 16px;
        }
        .hero-logo-placeholder {
            width: 100px; height: 100px; border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 36px; font-weight: 800; color: #fff;
            border: 4px solid rgba(255,255,255,0.3);
            margin-bottom: 16px;
        }
        .hero-uni-name { color: #fff; font-size: 18px; font-weight: 700; text-align: center; margin-bottom: 6px; }
        .hero-uni-sub { color: #B5D4F4; font-size: 13px; text-align: center; margin-bottom: 20px; }
        .hero-stats { display: flex; gap: 24px; }
        .hero-stat { text-align: center; }
        .hero-stat-num { color: #fff; font-size: 22px; font-weight: 700; }
        .hero-stat-label { color: #B5D4F4; font-size: 11px; margin-top: 2px; }

        /* HOW IT WORKS */
        .section { padding: 80px 48px; }
        .section-title { font-size: 30px; font-weight: 700; color: #0C447C; text-align: center; margin-bottom: 8px; }
        .section-sub { font-size: 15px; color: #64748b; text-align: center; margin-bottom: 48px; }
        .steps-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 24px; }
        .step-card {
            background: #fff; border-radius: 16px;
            padding: 24px 20px; text-align: center;
            border: 1px solid #e2e8f0;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .step-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .step-num {
            width: 48px; height: 48px; border-radius: 50%;
            background: linear-gradient(135deg, #0C447C, #185FA5);
            color: #fff; font-size: 18px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
        }
        .step-title { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
        .step-desc { font-size: 13px; color: #64748b; line-height: 1.6; }

        /* FAQ SECTION */
        .faq-section { background: #f8fafc; padding: 80px 48px; }
        .faq-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 24px; max-width: 1000px; margin: 0 auto; }
        .faq-card {
            background: #fff; border-radius: 16px;
            padding: 24px; border: 1px solid #e2e8f0;
        }
        .faq-icon { font-size: 28px; margin-bottom: 12px; }
        .faq-q { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
        .faq-a { font-size: 13px; color: #64748b; line-height: 1.6; }
        .btn-contact {
            display: inline-flex; align-items: center; gap: 6px;
            background: #185FA5; color: #fff;
            padding: 10px 20px; border-radius: 20px;
            font-size: 13px; font-weight: 600;
            text-decoration: none; margin-top: 14px;
        }

        /* DEPARTMENTS */
        .dept-section { padding: 80px 48px; background: #fff; }
        .dept-grid { display: grid; grid-template-columns: repeat(5,1fr); gap: 16px; }
        .dept-card {
            background: #f8fafc; border-radius: 14px;
            padding: 20px 16px; text-align: center;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }
        .dept-card:hover { background: #E6F1FB; border-color: #B5D4F4; transform: translateY(-2px); }
        .dept-icon { font-size: 32px; margin-bottom: 10px; }
        .dept-name { font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .dept-order { font-size: 11px; color: #94a3b8; }

        /* FOOTER */
        .footer {
            background: #0C447C;
            padding: 40px 48px;
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 20px;
        }
        .footer-brand { color: #fff; font-size: 15px; font-weight: 700; }
        .footer-sub { color: #B5D4F4; font-size: 12px; margin-top: 4px; }
        .footer-links { display: flex; gap: 20px; }
        .footer-link { color: #B5D4F4; font-size: 13px; text-decoration: none; }
        .footer-link:hover { color: #fff; }
        .footer-copy { color: #B5D4F4; font-size: 12px; }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar">
    <div class="navbar-brand">
        <img src="/images/brown.jpg" class="navbar-logo" alt=" Logo">
        <div>
            <div class="brand-name">Clearance Management System</div>
            <div class="brand-sub">Brown University of Technology</div>
        </div>
    </div>
    <div class="navbar-links">
        <a href="#how" class="nav-link">How it works</a>
        <a href="#departments" class="nav-link">Departments</a>
        <a href="#faq" class="nav-link">Need Help?</a>
        <a href="#faq" class="nav-link">Contact</a>
    </div>
    <div class="navbar-btns">
        <a href="{{ route('login.form') }}" class="btn-student">🎓 Login</a>
    </div>
</nav>

{{-- HERO --}}
<section class="hero">
    <div class="hero-content">
        <div class="hero-badge">🎓 Brown University of Technology — Official Portal</div>
        <h1 class="hero-title">
            Clearance,<br>
            <span>Simplified.</span>
        </h1>
        <p class="hero-desc">
            A smooth, stress-free way to finalize your student journey with clarity,
            support, and ease. Complete your clearance from all departments — online,
            anytime, anywhere.
        </p>
        <div class="hero-btns">
            <a href="{{ route('login.form') }}" class="btn-hero-primary">🎓 Get Started</a>
            <a href="#how" class="btn-hero-secondary">Learn More →</a>
        </div>
    </div>
    <div class="hero-image">
        <div class="hero-img-box">
            <div class="hero-logo-placeholder">U</div>
            <div class="hero-uni-name">Brown University of Technology</div>
            <div class="hero-uni-sub">Embracing Knowledge</div>
            <div class="hero-stats">

                <div class="hero-stat">
                    <div class="hero-stat-num">100%</div>
                    <div class="hero-stat-label">Digital</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-num">24/7</div>
                    <div class="hero-stat-label">Available</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="section" id="how">
    <div class="section-title">How It Works</div>
    <div class="section-sub">Complete your clearance in  simple steps</div>
    <div class="steps-grid">
        <div class="step-card">
            <div class="step-num">1</div>
            <div class="step-title">Login to Portal</div>
            <div class="step-desc">Sign in using your university registration number and password.</div>
        </div>
        <div class="step-card">
            <div class="step-num">2</div>
            <div class="step-title">Request Clearance</div>
            <div class="step-desc">Submit clearance requests to each department one by one in order.</div>
        </div>
        <div class="step-card">
            <div class="step-num">3</div>
            <div class="step-title">Clear Payments</div>
            <div class="step-desc">Pay any outstanding fees or penalties using your control numbers.</div>
        </div>
        <div class="step-card">
            <div class="step-num">4</div>
            <div class="step-title">Download Clearence Ticket</div>
            <div class="step-desc">Once all departments approve, download your official clearance certificate.</div>
        </div>
    </div>
</section>



{{-- FOOTER --}}
<footer class="footer">
    <div>
        <div class="footer-brand">Brown University of Technology </div>
        <div class="footer-sub">Embracing Knowledge | Dodoma, Tanzania</div>
    </div>
    <div class="footer-links">
        <a href="#" class="footer-link">Privacy Policy</a>
        <a href="#" class="footer-link">Terms of Use</a>
        <a href="mailto:helpdesk@udom.ac.tz" class="footer-link">helpdesk@reddixh.ac.tz</a>
        <a href="tel:+255262961000" class="footer-link">+255 74 747 6275</a>
    </div>
    <div class="footer-copy">© {{ date('Y') }} Brown University of Technology. All rights reserved.</div>
</footer>

</body>
</html>