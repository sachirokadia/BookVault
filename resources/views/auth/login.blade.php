<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — BookVault</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,400;0,600;0,700;1,400&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-deep: #1b4332;
            --green-mid:  #2d6a4f;
            --gold:       #d4a017;
            --parchment:  #faf7f0;
            --cream:      #f5f0e8;
            --border:     #d8cdb8;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Nunito', sans-serif;
            background: var(--parchment);
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }

        /* Left: decorative pattern panel */
        .hero-panel {
            background: var(--green-deep);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 50px;
        }
        .hero-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 80%, rgba(212,160,23,.18) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(64,145,108,.3) 0%, transparent 60%);
        }
        /* Decorative grid pattern */
        .hero-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .hero-content { position: relative; z-index: 1; }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(212,160,23,.2);
            border: 1px solid rgba(212,160,23,.4);
            color: var(--gold);
            padding: 6px 14px;
            border-radius: 99px;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 20px;
            width: fit-content;
        }
        .hero-title {
            font-family: 'Crimson Pro', serif;
            color: #fff;
            font-size: 3rem;
            line-height: 1.15;
            margin-bottom: 16px;
        }
        .hero-title em { color: var(--gold); font-style: italic; }
        .hero-desc { color: rgba(255,255,255,.65); font-size: .9rem; line-height: 1.8; margin-bottom: 32px; }

        .feature-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .feature-chip {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.1);
            color: rgba(255,255,255,.75);
            padding: 6px 14px;
            border-radius: 8px;
            font-size: .78rem;
            font-weight: 600;
        }
        .feature-chip i { color: var(--gold); }

        .hero-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,.1);
            color: rgba(255,255,255,.35);
            font-size: .72rem;
            letter-spacing: .5px;
        }

        /* Right: form panel */
        .form-panel {
            background: var(--parchment);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
        }
        .form-box { width: 100%; max-width: 380px; }

        .logo-mark {
            width: 52px; height: 52px;
            background: var(--green-deep);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            color: var(--gold);
            margin-bottom: 20px;
        }
        .form-title {
            font-family: 'Crimson Pro', serif;
            font-size: 2rem;
            color: var(--green-deep);
            font-weight: 700;
            margin-bottom: 4px;
        }
        .form-sub { font-size: .85rem; color: #777; margin-bottom: 28px; }

        label.lbl {
            font-size: .72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #555;
            margin-bottom: 6px;
            display: block;
        }
        .field-wrap {
            position: relative;
            margin-bottom: 16px;
        }
        .field-wrap i.field-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: .9rem;
            pointer-events: none;
        }
        .field-wrap input {
            width: 100%;
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 10px 14px 10px 38px;
            font-family: 'Nunito', sans-serif;
            font-size: .9rem;
            color: #1a1a2e;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .field-wrap input:focus {
            border-color: var(--green-mid);
            box-shadow: 0 0 0 3px rgba(45,106,79,.12);
        }
        .field-wrap .eye-btn {
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: #bbb; cursor: pointer; font-size: .9rem;
        }

        .btn-signin {
            width: 100%;
            background: var(--green-deep);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-family: 'Nunito', sans-serif;
            font-weight: 700;
            font-size: .95rem;
            cursor: pointer;
            transition: background .2s, transform .1s;
            margin-top: 8px;
        }
        .btn-signin:hover { background: var(--green-mid); transform: translateY(-1px); }
        .btn-signin:active { transform: translateY(0); }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: #ccc;
            font-size: .75rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .demo-box {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px 16px;
        }
        .demo-box .demo-title {
            font-size: .7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--green-deep);
            margin-bottom: 8px;
        }
        .demo-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: .78rem;
            padding: 3px 0;
            border-bottom: 1px dashed var(--border);
        }
        .demo-row:last-child { border-bottom: none; }
        .demo-row .role { color: #999; }
        .demo-row .cred {
            font-family: monospace;
            background: var(--cream);
            padding: 1px 6px;
            border-radius: 4px;
            color: var(--green-mid);
            font-size: .76rem;
        }

        .err-box {
            background: #fee2e2;
            border-radius: 8px;
            padding: 10px 14px;
            color: #991b1b;
            font-size: .83rem;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
<div class="row g-0 min-vh-100">

    {{-- Left Hero --}}
    <div class="col-lg-6 hero-panel d-none d-lg-flex flex-column justify-content-end">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="bi bi-bookmark-star-fill"></i>
                ITM SLS Baroda University
            </div>
            <h1 class="hero-title">
                The Modern<br><em>Library</em><br>Experience
            </h1>
            <p class="hero-desc">
                BookVault digitises your entire library operation —
                catalogue, issue tracking, member management and overdue
                monitoring — in one elegant system.
            </p>
            <div class="feature-row">
                <div class="feature-chip"><i class="bi bi-search"></i> Smart Search</div>
                <div class="feature-chip"><i class="bi bi-arrow-left-right"></i> Issue & Return</div>
                <div class="feature-chip"><i class="bi bi-shield-check"></i> Role-Based Access</div>
                <div class="feature-chip"><i class="bi bi-clock-history"></i> Overdue Alerts</div>
                <div class="feature-chip"><i class="bi bi-currency-rupee"></i> Fine Tracking</div>
            </div>
            <div class="hero-footer">Capstone Project &mdash; Class Assessment &bull; Laravel + Bootstrap + jQuery</div>
        </div>
    </div>

    {{-- Right Form --}}
    <div class="col-lg-6 form-panel">
        <div class="form-box">
            <div class="logo-mark"><i class="bi bi-bookmark-fill"></i></div>
            <div class="form-title">Welcome back</div>
            <div class="form-sub">Sign in to your BookVault account</div>

            @if($errors->any())
                <div class="err-box">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf

                <label class="lbl">Email Address</label>
                <div class="field-wrap">
                    <i class="field-icon bi bi-envelope"></i>
                    <input type="email" name="email" placeholder="admin@bookvault.com"
                           value="{{ old('email') }}" required autofocus>
                </div>

                <label class="lbl">Password</label>
                <div class="field-wrap">
                    <i class="field-icon bi bi-lock"></i>
                    <input type="password" name="password" id="pwdField"
                           placeholder="••••••••" required>
                    <button type="button" class="eye-btn" id="togglePwd">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label style="display:flex;align-items:center;gap:6px;font-size:.82rem;cursor:pointer">
                        <input type="checkbox" name="remember" style="accent-color:var(--green-mid)">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn-signin" id="submitBtn">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In to BookVault
                </button>
            </form>

            <div class="divider">Demo Credentials</div>

            <div class="demo-box">
                <div class="demo-title">Quick Access</div>
                <div class="demo-row">
                    <span class="role">Admin</span>
                    <span class="cred">admin@bookvault.com</span>
                    <span class="cred">password</span>
                </div>
                <div class="demo-row">
                    <span class="role">Librarian</span>
                    <span class="cred">librarian@bookvault.com</span>
                    <span class="cred">password</span>
                </div>
                <div class="demo-row">
                    <span class="role">Member</span>
                    <span class="cred">member@bookvault.com</span>
                    <span class="cred">password</span>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$('#togglePwd').on('click', function () {
    const f = document.getElementById('pwdField');
    const i = document.getElementById('eyeIcon');
    if (f.type === 'password') {
        f.type = 'text';
        i.className = 'bi bi-eye-slash';
    } else {
        f.type = 'password';
        i.className = 'bi bi-eye';
    }
});
$('#loginForm').on('submit', function () {
    $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Signing in...');
});
</script>
</body>
</html>
