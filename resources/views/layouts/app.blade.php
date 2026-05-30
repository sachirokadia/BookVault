<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BookVault') — Library System</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:ital,wght@0,400;0,600;0,700;1,400&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --green-deep:   #1b4332;
            --green-mid:    #2d6a4f;
            --green-light:  #40916c;
            --gold:         #d4a017;
            --gold-light:   #f2c94c;
            --parchment:    #faf7f0;
            --cream:        #f5f0e8;
            --cream-dark:   #ede4d3;
            --ink:          #1a1a2e;
            --ink-mid:      #3d3d5c;
            --ink-soft:     #6b6b8a;
            --border:       #d8cdb8;
            --white:        #ffffff;
            --red:          #c0392b;
            --amber:        #d97706;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--parchment);
            color: var(--ink);
            min-height: 100vh;
        }

        /* ── Top Navbar ── */
        .top-navbar {
            background: var(--green-deep);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 12px rgba(0,0,0,.25);
        }

        .navbar-brand-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 24px;
            height: 60px;
            border-right: 1px solid rgba(255,255,255,.1);
            text-decoration: none;
        }
        .navbar-brand-wrap .brand-icon {
            width: 36px; height: 36px;
            background: var(--gold);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: var(--green-deep);
            font-size: 1.1rem;
            font-weight: 900;
            flex-shrink: 0;
        }
        .navbar-brand-wrap .brand-text {
            font-family: 'Crimson Pro', serif;
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: .3px;
            line-height: 1;
        }
        .navbar-brand-wrap .brand-sub {
            font-size: .6rem;
            color: rgba(255,255,255,.5);
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .top-nav-links {
            display: flex;
            align-items: stretch;
            height: 60px;
            padding: 0 8px;
            flex: 1;
        }
        .top-nav-links .nav-item-link {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 0 16px;
            color: rgba(255,255,255,.7);
            font-size: .82rem;
            font-weight: 600;
            text-decoration: none;
            border-bottom: 3px solid transparent;
            transition: all .2s;
            white-space: nowrap;
        }
        .top-nav-links .nav-item-link i { font-size: .95rem; }
        .top-nav-links .nav-item-link:hover {
            color: #fff;
            background: rgba(255,255,255,.07);
            border-bottom-color: var(--gold);
        }
        .top-nav-links .nav-item-link.active {
            color: #fff;
            border-bottom-color: var(--gold);
            background: rgba(255,255,255,.05);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0 20px;
            border-left: 1px solid rgba(255,255,255,.1);
        }
        .user-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,.08);
            border-radius: 99px;
            padding: 4px 14px 4px 6px;
            cursor: pointer;
        }
        .user-chip .avatar {
            width: 30px; height: 30px;
            background: var(--gold);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
            font-size: .78rem;
            color: var(--green-deep);
        }
        .user-chip .uname { font-size: .78rem; color: #fff; font-weight: 600; }
        .user-chip .urole { font-size: .65rem; color: rgba(255,255,255,.55); }

        /* ── Breadcrumb bar ── */
        .breadbar {
            background: var(--cream);
            border-bottom: 1px solid var(--border);
            padding: 8px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .breadbar .page-heading {
            font-family: 'Crimson Pro', serif;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--green-deep);
        }
        .breadbar .breadcrumb {
            margin: 0; padding: 0;
            font-size: .75rem;
        }
        .breadbar .breadcrumb-item + .breadcrumb-item::before { color: #aaa; }
        .breadbar .breadcrumb-item a { color: var(--green-mid); text-decoration: none; }
        .breadbar .breadcrumb-item.active { color: var(--ink-soft); }

        /* ── Page wrapper ── */
        .page-wrap { padding: 24px 28px; }

        /* ── Cards ── */
        .card {
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--white);
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
        }
        .card-header {
            background: var(--cream);
            border-bottom: 1px solid var(--border);
            padding: 13px 20px;
            font-family: 'Crimson Pro', serif;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--green-deep);
            display: flex;
            align-items: center;
            gap: 8px;
            border-radius: 10px 10px 0 0 !important;
        }
        .card-header .badge-count {
            background: var(--green-deep);
            color: #fff;
            font-size: .65rem;
            padding: 2px 7px;
            border-radius: 99px;
            font-family: 'Nunito', sans-serif;
            font-weight: 700;
        }

        /* ── Stat tiles ── */
        .stat-tile {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            border-left: 5px solid transparent;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-tile:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,.08);
        }
        .stat-tile .tile-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 12px;
        }
        .stat-tile .tile-val {
            font-family: 'Crimson Pro', serif;
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1;
            color: var(--ink);
        }
        .stat-tile .tile-lbl {
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--ink-soft);
            margin-top: 2px;
        }

        .tile-emerald { border-left-color: var(--green-mid);  }
        .tile-emerald .tile-icon { background: #d1fae5; color: var(--green-mid); }
        .tile-gold    { border-left-color: var(--gold);       }
        .tile-gold    .tile-icon { background: #fef3c7; color: var(--amber); }
        .tile-red     { border-left-color: var(--red);        }
        .tile-red     .tile-icon { background: #fee2e2; color: var(--red); }
        .tile-slate   { border-left-color: #475569;           }
        .tile-slate   .tile-icon { background: #e2e8f0; color: #475569; }
        .tile-teal    { border-left-color: #0f766e;           }
        .tile-teal    .tile-icon { background: #ccfbf1; color: #0f766e; }
        .tile-violet  { border-left-color: #7c3aed;           }
        .tile-violet  .tile-icon { background: #ede9fe; color: #7c3aed; }

        /* ── Tables ── */
        .table thead th {
            background: var(--cream);
            font-family: 'Nunito', sans-serif;
            font-size: .7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--ink-soft);
            border-bottom: 2px solid var(--border);
            padding: 10px 14px;
        }
        .table tbody td {
            padding: 11px 14px;
            font-size: .865rem;
            vertical-align: middle;
            border-color: #f0ece3;
        }
        .table tbody tr:hover { background: #fdfaf4; }
        .table-striped tbody tr:nth-of-type(odd) { background: #faf7f0; }
        .table-striped tbody tr:nth-of-type(odd):hover { background: #f5f0e3; }

        /* ── Badges ── */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: .72rem;
            font-weight: 700;
        }
        .pill-issued   { background: #dbeafe; color: #1d4ed8; }
        .pill-returned { background: #dcfce7; color: #166534; }
        .pill-overdue  { background: #fee2e2; color: #991b1b; }
        .pill-active   { background: #dcfce7; color: #166534; }
        .pill-inactive { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }

        /* ── Buttons ── */
        .btn-emerald {
            background: var(--green-mid);
            border: none;
            color: #fff;
            font-weight: 700;
            border-radius: 8px;
            transition: background .2s;
        }
        .btn-emerald:hover { background: var(--green-deep); color: #fff; }

        .btn-gold {
            background: var(--gold);
            border: none;
            color: var(--green-deep);
            font-weight: 700;
            border-radius: 8px;
        }
        .btn-gold:hover { background: #b8860b; color: var(--green-deep); }

        .btn-outline-emerald {
            border: 1.5px solid var(--green-mid);
            color: var(--green-mid);
            background: transparent;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-outline-emerald:hover { background: var(--green-mid); color: #fff; }

        /* ── Forms ── */
        .form-label {
            font-size: .8rem;
            font-weight: 700;
            color: var(--ink-mid);
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .form-control, .form-select {
            border: 1.5px solid var(--border);
            border-radius: 8px;
            background: var(--parchment);
            font-size: .875rem;
            padding: 9px 13px;
            color: var(--ink);
            transition: border-color .2s, background .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--green-mid);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(45,106,79,.12);
        }
        .input-group-text {
            background: var(--cream);
            border-color: var(--border);
            color: var(--ink-soft);
            border-radius: 8px 0 0 8px;
        }

        /* ── Alerts ── */
        .alert { border-radius: 10px; font-size: .875rem; border: none; }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-danger  { background: #fee2e2; color: #991b1b; }

        /* ── Dropdown ── */
        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,.1);
            padding: 6px;
            font-size: .85rem;
        }
        .dropdown-item { border-radius: 6px; padding: 7px 12px; font-weight: 500; }
        .dropdown-item:hover { background: var(--cream); color: var(--green-deep); }
        .dropdown-divider { border-color: var(--border); }

        /* ── Action icon buttons ── */
        .btn-icon {
            width: 30px; height: 30px;
            border-radius: 7px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: .85rem;
            border: 1.5px solid transparent;
            transition: all .15s;
            text-decoration: none;
            cursor: pointer;
            background: none;
        }
        .btn-icon-view   { border-color: #93c5fd; color: #2563eb; background: #eff6ff; }
        .btn-icon-view:hover  { background: #2563eb; color: #fff; border-color: #2563eb; }
        .btn-icon-edit   { border-color: #fcd34d; color: #d97706; background: #fffbeb; }
        .btn-icon-edit:hover  { background: #d97706; color: #fff; border-color: #d97706; }
        .btn-icon-del    { border-color: #fca5a5; color: #dc2626; background: #fff1f2; }
        .btn-icon-del:hover   { background: #dc2626; color: #fff; border-color: #dc2626; }
        .btn-icon-return { border-color: #6ee7b7; color: #059669; background: #ecfdf5; }
        .btn-icon-return:hover { background: #059669; color: #fff; border-color: #059669; }

        /* ── Decorative section divider ── */
        .section-rule {
            height: 3px;
            background: linear-gradient(90deg, var(--green-deep), var(--gold), transparent);
            border: none;
            border-radius: 99px;
            margin: 0 0 20px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .top-nav-links { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── Top Navbar ── --}}
<nav class="top-navbar d-flex align-items-center">
    <a href="{{ route('dashboard') }}" class="navbar-brand-wrap text-decoration-none">
        <div class="brand-icon"><i class="bi bi-bookmark-fill"></i></div>
        <div>
            <div class="brand-text">BookVault</div>
            <div class="brand-sub">Library System</div>
        </div>
    </a>

    <div class="top-nav-links">
        <a href="{{ route('dashboard') }}"
           class="nav-item-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="{{ route('books.index') }}"
           class="nav-item-link {{ request()->routeIs('books.*') ? 'active' : '' }}">
            <i class="bi bi-journal-richtext"></i> Catalogue
        </a>
        <a href="{{ route('issues.create') }}"
           class="nav-item-link {{ request()->routeIs('issues.create') ? 'active' : '' }}">
            <i class="bi bi-send"></i> Issue Book
        </a>
        <a href="{{ route('issues.index') }}"
           class="nav-item-link {{ request()->routeIs('issues.index') ? 'active' : '' }}">
            <i class="bi bi-card-list"></i> Transactions
        </a>
        <a href="{{ route('issues.overdue') }}"
           class="nav-item-link {{ request()->routeIs('issues.overdue') ? 'active' : '' }}">
            <i class="bi bi-hourglass-split"></i> Overdue
        </a>
        <a href="{{ route('members.index') }}"
           class="nav-item-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill"></i> Members
        </a>
    </div>

    <div class="navbar-right ms-auto">
        <div class="dropdown">
            <div class="user-chip" data-bs-toggle="dropdown">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}</div>
                <div>
                    <div class="uname">{{ Auth::user()->name ?? 'Admin' }}</div>
                    <div class="urole">{{ ucfirst(Auth::user()->role ?? 'admin') }}</div>
                </div>
                <i class="bi bi-chevron-down ms-1" style="color:rgba(255,255,255,.4);font-size:.65rem"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <span class="dropdown-item-text text-muted" style="font-size:.75rem;padding:6px 12px">
                        Signed in as <strong>{{ Auth::user()->email ?? '' }}</strong>
                    </span>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger" type="submit">
                            <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- ── Breadcrumb bar ── --}}
<div class="breadbar">
    <span class="page-heading">@yield('page-title', 'Dashboard')</span>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a>
            </li>
            @yield('breadcrumb')
        </ol>
    </nav>
</div>

{{-- ── Page Content ── --}}
<div class="page-wrap">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-exclamation-octagon-fill"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    // Auto-dismiss alerts
    setTimeout(() => $('.alert').alert('close'), 4500);
});
</script>
@stack('scripts')
</body>
</html>
