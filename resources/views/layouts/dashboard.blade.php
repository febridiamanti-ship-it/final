{{-- resources/views/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BaKos Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    @stack('styles')

    <style>
        /* ═══════════════════════════════════════════════
           ROOT VARIABLES – Enhanced Color System
           ═══════════════════════════════════════════════ */
        :root {
            /* Backgrounds */
            --bg-primary:    #0a0e17;
            --bg-secondary:  #111827;
            --bg-tertiary:   #1a2236;
            --bg-card:       #141c2b;
            --bg-card-hover: #182035;
            --bg-elevated:   #1e2a3f;

            /* Borders */
            --border:        rgba(255,255,255,0.06);
            --border-light:  rgba(255,255,255,0.10);
            --border-focus:  rgba(0,212,255,0.35);

            /* Accent Colors */
            --cyan:          #00d4ff;
            --cyan-dim:      rgba(0,212,255,0.10);
            --cyan-glow:     rgba(0,212,255,0.20);
            --cyan-strong:   rgba(0,212,255,0.35);
            --purple:        #a855f7;
            --purple-dim:    rgba(168,85,247,0.10);
            --purple-glow:   rgba(168,85,247,0.20);
            --green:         #10b981;
            --green-dim:     rgba(16,185,129,0.10);
            --green-glow:    rgba(16,185,129,0.20);
            --yellow:        #f59e0b;
            --yellow-dim:    rgba(245,158,11,0.10);
            --red:           #ef4444;
            --red-dim:       rgba(239,68,68,0.10);
            --red-glow:      rgba(239,68,68,0.20);
            --blue:          #3b82f6;
            --blue-dim:      rgba(59,130,246,0.10);

            /* Text */
            --text-primary:  #f0f4f8;
            --text-secondary:#8b9ab5;
            --text-muted:    #5a6a85;
            --text-white:    #ffffff;

            /* Layout */
            --sidebar-w:     260px;
            --sidebar-collapsed-w: 72px;
            --topbar-h:      64px;

            /* Radius */
            --radius:        12px;
            --radius-lg:     16px;
            --radius-xl:     20px;
            --radius-2xl:    24px;

            /* Transitions */
            --transition:    all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-spring: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            --transition-slow: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* ═══ RESET & BASE ═══ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ═══ ANIMATIONS ═══ */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(24px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-24px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.92); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.9); opacity: 1; }
            100% { transform: scale(1.8); opacity: 0; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
        @keyframes glow-pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        @keyframes slideToast {
            from { opacity: 0; transform: translateX(40px) scale(0.95); }
            to { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes slideToastOut {
            to { opacity: 0; transform: translateX(40px) scale(0.95); }
        }
        @keyframes progress-shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
        @keyframes border-glow {
            0%, 100% { border-color: rgba(0,212,255,0.15); }
            50% { border-color: rgba(0,212,255,0.3); }
        }
        @keyframes countUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in     { animation: fadeIn 0.4s ease both; }
        .animate-fade-in-up  { animation: fadeInUp 0.5s ease both; }
        .animate-fade-in-down{ animation: fadeInDown 0.4s ease both; }
        .animate-slide-right { animation: slideInRight 0.4s ease both; }
        .animate-slide-left  { animation: slideInLeft 0.4s ease both; }
        .animate-scale-in    { animation: scaleIn 0.35s ease both; }
        .animate-float       { animation: float 6s ease-in-out infinite; }

        .delay-75  { animation-delay: 75ms; }
        .delay-100 { animation-delay: 100ms; }
        .delay-150 { animation-delay: 150ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-500 { animation-delay: 500ms; }


        /* ═══════════════════════════════════════════════
           SIDEBAR – Refined with Micro-interactions
           ═══════════════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 100;
            transition: var(--transition-slow);
        }

        /* Subtle gradient overlay on sidebar */
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 200px;
            background: linear-gradient(180deg, rgba(0,212,255,0.03) 0%, transparent 100%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── Logo ── */
        .sidebar-logo {
            padding: 1.25rem 1.25rem 1.15rem;
            border-bottom: 1px solid var(--border);
            position: relative;
            z-index: 1;
        }
        .sidebar-logo a {
            display: flex;
            align-items: center;
            gap: 11px;
            text-decoration: none;
            transition: var(--transition);
        }
        .sidebar-logo a:hover .logo-icon {
            transform: scale(1.08) rotate(-3deg);
            box-shadow: 0 0 24px var(--cyan-glow), 0 0 48px rgba(0,212,255,0.1);
        }
        .logo-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--cyan), #0094b3);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px var(--cyan-glow);
            flex-shrink: 0;
            transition: var(--transition-spring);
        }
        .logo-icon i {
            color: var(--bg-primary);
            font-size: 1rem;
        }
        .logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }
        .logo-text span { color: var(--cyan); }
        .logo-sub {
            font-size: 9px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-top: -1px;
            font-weight: 600;
        }

        /* ── Navigation ── */
        .sidebar-nav {
            flex: 1;
            padding: 1rem .75rem;
            overflow-y: auto;
            overflow-x: hidden;
            position: relative;
            z-index: 1;
        }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--bg-tertiary); border-radius: 99px; }

        .nav-section-label {
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: .3rem .75rem;
            margin-bottom: .4rem;
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        .nav-section-label:first-child { margin-top: 0; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: .65rem .875rem;
            border-radius: var(--radius);
            color: var(--text-secondary);
            font-size: .855rem;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            margin-bottom: 2px;
            position: relative;
            overflow: hidden;
        }

        /* Hover ripple effect */
        .sidebar-link::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(255,255,255,0.06) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
        .sidebar-link:hover::after { opacity: 1; }

        .sidebar-link:hover {
            color: var(--text-primary);
            background: var(--bg-tertiary);
            transform: translateX(2px);
        }

        .sidebar-link.active {
            color: var(--cyan);
            background: var(--cyan-dim);
            font-weight: 600;
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 18%; bottom: 18%;
            width: 3px;
            background: var(--cyan);
            border-radius: 0 4px 4px 0;
            box-shadow: 0 0 12px var(--cyan-glow);
            animation: glow-pulse 2s ease-in-out infinite;
        }

        .sidebar-link i {
            font-size: 1.05rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
            transition: var(--transition);
        }
        .sidebar-link:hover i { transform: scale(1.1); }
        .sidebar-link.active i { filter: drop-shadow(0 0 4px var(--cyan-glow)); }

        .badge-nav {
            margin-left: auto;
            font-size: .6rem;
            font-weight: 700;
            background: var(--cyan-dim);
            color: var(--cyan);
            border-radius: 50px;
            padding: 2px 9px;
            border: 1px solid rgba(0,212,255,.15);
            letter-spacing: 0.5px;
            animation: border-glow 3s ease infinite;
        }

        /* ── User section at bottom ── */
        .sidebar-user {
            padding: .875rem .75rem;
            border-top: 1px solid var(--border);
            position: relative;
            z-index: 1;
        }
        .sidebar-user-inner {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: .625rem .875rem;
            border-radius: var(--radius);
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            transition: var(--transition);
        }
        .sidebar-user-inner:hover {
            border-color: var(--border-light);
            background: var(--bg-elevated);
        }
        .sidebar-user-inner img {
            width: 34px; height: 34px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid var(--cyan-dim);
            flex-shrink: 0;
            transition: var(--transition);
        }
        .sidebar-user-inner:hover img {
            border-color: var(--cyan-glow);
            box-shadow: 0 0 12px var(--cyan-dim);
        }
        .sidebar-user-name {
            font-size: .8rem;
            font-weight: 600;
            color: var(--text-primary);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .sidebar-user-role {
            font-size: .65rem;
            color: var(--text-muted);
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        .sidebar-logout {
            margin-left: auto;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sidebar-logout:hover {
            color: var(--red);
            background: var(--red-dim);
            transform: scale(1.1);
        }


        /* ═══════════════════════════════════════════════
           MAIN AREA
           ═══════════════════════════════════════════════ */
        .main-area {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: var(--transition-slow);
        }

        /* ── Topbar ── */
        .topbar {
            height: var(--topbar-h);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            background: rgba(17, 24, 39, 0.8);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 99;
            animation: fadeInDown 0.4s ease both;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .855rem;
            color: var(--text-muted);
        }
        .topbar-breadcrumb a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color .2s;
        }
        .topbar-breadcrumb a:hover { color: var(--cyan); }
        .topbar-breadcrumb .sep { opacity: .35; font-size: .75rem; }
        .topbar-current {
            color: var(--text-primary);
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .topbar-btn {
            width: 38px; height: 38px;
            border-radius: var(--radius);
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        .topbar-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0,212,255,0.1) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .topbar-btn:hover::before { opacity: 1; }
        .topbar-btn:hover {
            color: var(--cyan);
            border-color: rgba(0,212,255,0.25);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .topbar-btn:active {
            transform: translateY(0) scale(0.95);
        }
        .topbar-btn i { position: relative; z-index: 1; font-size: 1rem; }

        /* Topbar notification dot */
        .topbar-btn .notif-dot {
            position: absolute;
            top: 7px; right: 7px;
            width: 7px; height: 7px;
            background: var(--red);
            border-radius: 50%;
            border: 1.5px solid var(--bg-secondary);
            box-shadow: 0 0 6px var(--red-glow);
        }

        .topbar-avatar {
            width: 34px; height: 34px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid var(--border);
            transition: var(--transition);
            cursor: pointer;
        }
        .topbar-avatar:hover {
            border-color: var(--cyan-glow);
            box-shadow: 0 0 16px var(--cyan-dim);
            transform: scale(1.05);
        }

        /* ── Content Area ── */
        .content-area {
            flex: 1;
            padding: 2rem;
            animation: fadeInUp 0.5s ease both;
            animation-delay: 0.1s;
        }


        /* ═══════════════════════════════════════════════
           COMPONENT LIBRARY
           ═══════════════════════════════════════════════ */

        /* ── Cards ── */
        .card-dark {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: var(--transition);
            position: relative;
        }
        .card-dark::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at top, rgba(0,212,255,0.02) 0%, transparent 70%);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.4s;
        }
        .card-dark:hover {
            border-color: var(--border-light);
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }
        .card-dark:hover::before { opacity: 1; }

        .card-header-dark {
            padding: 1.15rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255,255,255,0.01);
        }
        .card-title-dark {
            font-weight: 700;
            font-size: .95rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .dot-cyan   { background: var(--cyan);   box-shadow: 0 0 8px var(--cyan-glow); }
        .dot-purple { background: var(--purple); box-shadow: 0 0 8px var(--purple-glow); }
        .dot-green  { background: var(--green);  box-shadow: 0 0 8px var(--green-glow); }
        .dot-red    { background: var(--red);    box-shadow: 0 0 8px var(--red-glow); }
        .dot-yellow { background: var(--yellow); box-shadow: 0 0 8px rgba(245,158,11,0.4); }

        /* ── Stat Cards ── */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 1.5rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
        }
        /* Subtle grid pattern */
        .stat-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 20px 20px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.4s;
        }
        .stat-card:hover::after { opacity: 1; }

        .stat-cyan::before   { background: linear-gradient(90deg, var(--cyan), transparent); }
        .stat-purple::before { background: linear-gradient(90deg, var(--purple), transparent); }
        .stat-green::before  { background: linear-gradient(90deg, var(--green), transparent); }
        .stat-yellow::before { background: linear-gradient(90deg, var(--yellow), transparent); }
        .stat-red::before    { background: linear-gradient(90deg, var(--red), transparent); }

        .stat-card:hover {
            border-color: var(--border-light);
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.25);
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            position: relative;
            transition: var(--transition);
        }
        .stat-card:hover .stat-icon { transform: scale(1.08); }

        /* Icon glow ring on hover */
        .stat-icon::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 16px;
            border: 2px solid transparent;
            transition: var(--transition);
        }
        .stat-card.stat-cyan:hover .stat-icon::after   { border-color: var(--cyan-dim); }
        .stat-card.stat-purple:hover .stat-icon::after  { border-color: var(--purple-dim); }
        .stat-card.stat-green:hover .stat-icon::after   { border-color: var(--green-dim); }
        .stat-card.stat-yellow:hover .stat-icon::after  { border-color: var(--yellow-dim); }

        .stat-number {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.1rem;
            font-weight: 700;
            line-height: 1;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            animation: countUp 0.6s ease both;
        }
        .stat-label {
            font-size: .72rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-top: 5px;
        }
        .stat-sub {
            font-size: .72rem;
            color: var(--text-muted);
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .stat-change-up {
            color: var(--green);
            font-weight: 600;
            font-size: .7rem;
        }
        .stat-change-down {
            color: var(--red);
            font-weight: 600;
            font-size: .7rem;
        }

        /* ── Badges ── */
        .badge-base {
            font-size: .66rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 50px;
            white-space: nowrap;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: var(--transition);
        }
        .badge-cyan   { background: var(--cyan-dim);   color: var(--cyan);   border: 1px solid rgba(0,212,255,.12); }
        .badge-purple { background: var(--purple-dim); color: var(--purple); border: 1px solid rgba(168,85,247,.12); }
        .badge-green  { background: var(--green-dim);  color: var(--green);  border: 1px solid rgba(16,185,129,.12); }
        .badge-red    { background: var(--red-dim);    color: var(--red);    border: 1px solid rgba(239,68,68,.12); }
        .badge-yellow { background: var(--yellow-dim); color: var(--yellow); border: 1px solid rgba(245,158,11,.12); }
        .badge-blue   { background: var(--blue-dim);   color: var(--blue);   border: 1px solid rgba(59,130,246,.12); }

        /* ── Table ── */
        .table-dash {
            width: 100%;
            border-collapse: collapse;
        }
        .table-dash thead {
            position: sticky;
            top: 0;
            z-index: 2;
        }
        .table-dash th {
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-muted);
            padding: .875rem 1.25rem;
            border-bottom: 1px solid var(--border);
            background: var(--bg-card);
            white-space: nowrap;
            text-align: left;
        }
        .table-dash td {
            padding: .9rem 1.25rem;
            border-bottom: 1px solid var(--border);
            font-size: .875rem;
            color: var(--text-secondary);
            vertical-align: middle;
            transition: var(--transition);
        }
        .table-dash tbody tr {
            transition: var(--transition);
        }
        .table-dash tbody tr:hover {
            background: rgba(0,212,255,0.02);
        }
        .table-dash tbody tr:hover td {
            color: var(--text-primary);
        }
        .table-dash tbody tr:last-child td { border-bottom: none; }

        /* Row stagger animation */
        .table-dash tbody tr {
            animation: fadeInUp 0.4s ease both;
        }
        .table-dash tbody tr:nth-child(1) { animation-delay: 0.05s; }
        .table-dash tbody tr:nth-child(2) { animation-delay: 0.1s; }
        .table-dash tbody tr:nth-child(3) { animation-delay: 0.15s; }
        .table-dash tbody tr:nth-child(4) { animation-delay: 0.2s; }
        .table-dash tbody tr:nth-child(5) { animation-delay: 0.25s; }
        .table-dash tbody tr:nth-child(n+6) { animation-delay: 0.3s; }

        /* ── Form Inputs ── */
        .input-dark {
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text-primary);
            padding: .7rem 1rem;
            font-size: .875rem;
            font-family: 'Inter', sans-serif;
            width: 100%;
            transition: var(--transition);
            outline: none;
        }
        .input-dark::placeholder { color: var(--text-muted); }
        .input-dark:hover { border-color: var(--border-light); }
        .input-dark:focus {
            border-color: var(--cyan);
            box-shadow: 0 0 0 3px var(--cyan-dim), 0 0 20px rgba(0,212,255,0.05);
        }

        .select-dark {
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text-primary);
            padding: .7rem 1rem;
            font-size: .875rem;
            font-family: 'Inter', sans-serif;
            width: 100%;
            transition: var(--transition);
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%235a6a85' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 2.5rem;
        }
        .select-dark:hover { border-color: var(--border-light); }
        .select-dark:focus {
            border-color: var(--cyan);
            box-shadow: 0 0 0 3px var(--cyan-dim);
        }

        .label-dark {
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
            display: block;
            letter-spacing: 0.2px;
        }

        /* ── Buttons ── */
        .btn-cyan {
            background: linear-gradient(135deg, var(--cyan), #0094b3);
            color: var(--bg-primary);
            font-weight: 700;
            font-size: .875rem;
            border: none;
            border-radius: var(--radius);
            padding: .65rem 1.35rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 16px var(--cyan-glow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        .btn-cyan::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .btn-cyan:hover::before { opacity: 1; }
        .btn-cyan:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px var(--cyan-strong);
            color: var(--bg-primary);
        }
        .btn-cyan:active {
            transform: translateY(0) scale(0.97);
        }

        .btn-ghost-dark {
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            font-size: .875rem;
            font-weight: 500;
            border-radius: var(--radius);
            padding: .65rem 1.35rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }
        .btn-ghost-dark:hover {
            color: var(--text-primary);
            border-color: var(--border-light);
            background: var(--bg-elevated);
            transform: translateY(-1px);
        }
        .btn-ghost-dark:active { transform: translateY(0); }

        .btn-danger {
            background: var(--red-dim);
            border: 1px solid rgba(239,68,68,0.15);
            color: var(--red);
            font-size: .875rem;
            font-weight: 600;
            border-radius: var(--radius);
            padding: .65rem 1.35rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }
        .btn-danger:hover {
            background: rgba(239,68,68,0.18);
            border-color: rgba(239,68,68,0.25);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px var(--red-glow);
        }

        /* ── Action icon buttons ── */
        .btn-icon {
            width: 32px; height: 32px;
            border-radius: 9px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            font-size: .82rem;
            position: relative;
        }
        .btn-icon-cyan   { background: var(--cyan-dim);   color: var(--cyan); }
        .btn-icon-yellow { background: var(--yellow-dim); color: var(--yellow); }
        .btn-icon-red    { background: var(--red-dim);    color: var(--red); }
        .btn-icon-green  { background: var(--green-dim);  color: var(--green); }
        .btn-icon-purple { background: var(--purple-dim); color: var(--purple); }
        .btn-icon:hover {
            transform: scale(1.12) translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .btn-icon:active { transform: scale(0.95); }

        /* ── Tooltip for btn-icon ── */
        .btn-icon[title] { position: relative; }
        .btn-icon[title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: .68rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            white-space: nowrap;
            border: 1px solid var(--border);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            pointer-events: none;
            animation: fadeIn 0.15s ease;
            z-index: 100;
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }
        .empty-state-icon {
            width: 64px; height: 64px;
            border-radius: 20px;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 1.5rem;
            color: var(--text-muted);
            animation: float 6s ease-in-out infinite;
        }
        .empty-state-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: .5rem;
        }
        .empty-state-desc {
            font-size: .85rem;
            color: var(--text-muted);
            max-width: 320px;
            margin: 0 auto;
            line-height: 1.6;
        }


        /* ═══════════════════════════════════════════════
           TOAST NOTIFICATIONS
           ═══════════════════════════════════════════════ */
        .toast-container {
            position: fixed;
            top: calc(var(--topbar-h) + 12px);
            right: 20px;
            z-index: 9999;
        }
        .toast-custom {
            background: var(--bg-secondary);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            max-width: 380px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4), 0 0 1px rgba(255,255,255,0.1) inset;
            animation: slideToast 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) both;
            position: relative;
            overflow: hidden;
        }
        .toast-custom.hide {
            animation: slideToastOut 0.3s ease forwards;
        }
        .toast-custom.toast-success {
            border-left: 3px solid var(--green);
        }
        .toast-custom.toast-error {
            border-left: 3px solid var(--red);
        }

        .toast-icon {
            width: 34px; height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .95rem;
        }
        .toast-icon-success {
            background: var(--green-dim);
            color: var(--green);
            border: 1px solid rgba(16,185,129,.15);
        }
        .toast-icon-error {
            background: var(--red-dim);
            color: var(--red);
            border: 1px solid rgba(239,68,68,.15);
        }

        .toast-content { flex: 1; min-width: 0; }
        .toast-title {
            font-weight: 700;
            font-size: .875rem;
            color: var(--text-primary);
        }
        .toast-msg {
            font-size: .8rem;
            color: var(--text-muted);
            margin-top: 3px;
            line-height: 1.4;
        }
        .toast-close {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .toast-close:hover {
            color: var(--text-primary);
            background: var(--bg-tertiary);
        }

        /* Toast progress bar */
        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2px;
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
        }
        .toast-progress-success {
            background: var(--green);
            animation: progress-shrink 5s linear forwards;
        }
        .toast-progress-error {
            background: var(--red);
            animation: progress-shrink 5s linear forwards;
        }


        /* ═══════════════════════════════════════════════
           SCROLLBAR
           ═══════════════════════════════════════════════ */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--bg-tertiary); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* Leaflet */
        .leaflet-container { border-radius: var(--radius-lg); }

        /* ═══════════════════════════════════════════════
           RESPONSIVE
           ═══════════════════════════════════════════════ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 99;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            transition: opacity 0.3s;
        }

        .mobile-toggle { display: none; }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 20px 0 60px rgba(0,0,0,0.5);
            }
            .main-area { margin-left: 0; }
            .mobile-toggle { display: flex; }
            .content-area { padding: 1.25rem; }
            .topbar { padding: 0 1.25rem; }
        }

        @media (max-width: 640px) {
            .content-area { padding: 1rem; }
            .stat-number { font-size: 1.6rem; }
            .stat-card { padding: 1.25rem; }
        }


        /* ═══════════════════════════════════════════════
           UTILITY CLASSES
           ═══════════════════════════════════════════════ */
        .grid-cols-1 { display: grid; grid-template-columns: repeat(1, 1fr); }
        .grid-cols-2 { display: grid; grid-template-columns: repeat(2, 1fr); }
        .grid-cols-3 { display: grid; grid-template-columns: repeat(3, 1fr); }
        .grid-cols-4 { display: grid; grid-template-columns: repeat(4, 1fr); }
        .gap-4 { gap: 1rem; }
        .gap-5 { gap: 1.25rem; }
        .gap-6 { gap: 1.5rem; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .flex-wrap { flex-wrap: wrap; }
        .text-center { text-align: center; }
        .mt-2 { margin-top: .5rem; }
        .mt-4 { margin-top: 1rem; }
        .mt-6 { margin-top: 1.5rem; }
        .mb-2 { margin-bottom: .5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }

        /* Page header */
        .page-header {
            margin-bottom: 2rem;
        }
        .page-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }
        .page-subtitle {
            font-size: .875rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Divider with label */
        .divider-label {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.5rem 0;
        }
        .divider-label::before,
        .divider-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        .divider-label span {
            font-size: .7rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            white-space: nowrap;
        }

        /* Gradient text */
        .text-gradient-cyan {
            background: linear-gradient(135deg, var(--cyan), #0094b3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @media (max-width: 1024px) {
            .grid-cols-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-cols-3 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 640px) {
            .grid-cols-4 { grid-template-columns: 1fr; }
            .grid-cols-3 { grid-template-columns: 1fr; }
            .grid-cols-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- ═══════════════════════════════════════════
         SIDEBAR
         ═══════════════════════════════════════════ -->
    <aside class="sidebar" id="sidebar">

        <!-- Logo -->
        <div class="sidebar-logo">
            <a href="{{ route('home') }}">
                <div class="logo-icon">
                    <i class="bi bi-house-fill"></i>
                </div>
                <div>
                    <div class="logo-text"><span>Ba</span>Kos</div>
                    <div class="logo-sub">Dashboard</div>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            @if(auth()->user()->isAdmin())
                <div class="nav-section-label">Overview</div>
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                   style="animation: fadeInUp 0.4s ease 0.05s both;">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>

                <div class="nav-section-label">Kelola</div>
                <a href="{{ route('admin.users') }}"
                   class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}"
                   style="animation: fadeInUp 0.4s ease 0.1s both;">
                    <i class="bi bi-people-fill"></i> Pengguna
                </a>
                <a href="{{ route('admin.kos') }}"
                   class="sidebar-link {{ request()->routeIs('admin.kos') ? 'active' : '' }}"
                   style="animation: fadeInUp 0.4s ease 0.15s both;">
                    <i class="bi bi-house-door-fill"></i> Data Kos
                </a>

                <div class="nav-section-label">Konten</div>
                <a href="{{ route('kos.create') }}"
                   class="sidebar-link {{ request()->routeIs('kos.create') ? 'active' : '' }}"
                   style="animation: fadeInUp 0.4s ease 0.2s both;">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Kos
                </a>
                <a href="{{ route('kos.index') }}"
                   class="sidebar-link"
                   style="animation: fadeInUp 0.4s ease 0.25s both;">
                    <i class="bi bi-search"></i> Lihat Listing
                </a>

            @elseif(auth()->user()->isPemilik())
                <div class="nav-section-label">Overview</div>
                <a href="{{ route('pemilik.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('pemilik.dashboard') ? 'active' : '' }}"
                   style="animation: fadeInUp 0.4s ease 0.05s both;">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                <div class="nav-section-label">Kos Saya</div>
                <a href="{{ route('kos.create') }}"
                   class="sidebar-link {{ request()->routeIs('kos.create') ? 'active' : '' }}"
                   style="animation: fadeInUp 0.4s ease 0.1s both;">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Kos
                </a>
                <a href="{{ route('kos.index') }}"
                   class="sidebar-link"
                   style="animation: fadeInUp 0.4s ease 0.15s both;">
                    <i class="bi bi-search"></i> Lihat Semua Kos
                </a>
            @endif

            <div class="nav-section-label">Akun</div>
            <a href="{{ route('profile.show') }}"
               class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
               style="animation: fadeInUp 0.4s ease 0.3s both;">
                <i class="bi bi-person-circle"></i> Profil Saya
            </a>
            <a href="{{ route('favorites.index') }}"
               class="sidebar-link {{ request()->routeIs('favorites.*') ? 'active' : '' }}"
               style="animation: fadeInUp 0.4s ease 0.35s both;">
                <i class="bi bi-heart"></i> Kos Favorit
            </a>
            <a href="{{ route('home') }}"
               class="sidebar-link"
               style="animation: fadeInUp 0.4s ease 0.4s both;">
                <i class="bi bi-globe2"></i> Website
            </a>
        </nav>

        <!-- User -->
        <div class="sidebar-user">
            <div class="sidebar-user-inner">
                <img src="{{ auth()->user()->foto_profil_url }}" alt="{{ auth()->user()->name }}">
                <div style="flex:1;min-width:0;">
                    <div class="sidebar-user-name">{{ Str::limit(auth()->user()->name, 16) }}</div>
                    <div class="sidebar-user-role">{{ auth()->user()->role_label }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-logout" title="Keluar">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- ═══════════════════════════════════════════
         MAIN AREA
         ═══════════════════════════════════════════ -->
    <div class="main-area">

        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-left">
                <button onclick="toggleSidebar()" class="topbar-btn mobile-toggle" aria-label="Toggle sidebar">
                    <i class="bi bi-list" style="font-size:1.15rem;"></i>
                </button>
                <div class="topbar-breadcrumb">
                    <i class="bi bi-house" style="font-size:.78rem;"></i>
                    <span class="sep">›</span>
                    <a href="{{ route('home') }}">BaKos</a>
                    <span class="sep">›</span>
                    <span class="topbar-current">@yield('page_title', 'Dashboard')</span>
                </div>
            </div>

            <div class="topbar-right">
                {{-- Quick search button --}}
                <button class="topbar-btn" title="Pencarian" onclick="document.getElementById('quickSearchModal')?.classList.toggle('show')">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ route('kos.create') }}" class="topbar-btn" title="Tambah Kos">
                    <i class="bi bi-plus-lg"></i>
                </a>
                <a href="{{ route('favorites.index') }}" class="topbar-btn" title="Favorit">
                    <i class="bi bi-heart"></i>
                </a>
                {{-- Divider --}}
                <div style="width:1px;height:24px;background:var(--border);margin:0 .25rem;"></div>
                <a href="{{ route('profile.show') }}">
                    <img src="{{ auth()->user()->foto_profil_url }}"
                         alt="{{ auth()->user()->name }}"
                         class="topbar-avatar">
                </a>
            </div>
        </div>

        <!-- ── Toast Notifications ── -->
        <div class="toast-container">
            @if(session('success'))
            <div class="toast-custom toast-success" id="toast">
                <div class="toast-icon toast-icon-success">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">Berhasil!</div>
                    <div class="toast-msg">{{ session('success') }}</div>
                </div>
                <button onclick="closeToast()" class="toast-close">
                    <i class="bi bi-x-lg" style="font-size:.8rem;"></i>
                </button>
                <div class="toast-progress toast-progress-success"></div>
            </div>
            @endif

            @if(session('error'))
            <div class="toast-custom toast-error" id="toast">
                <div class="toast-icon toast-icon-error">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">Oops, terjadi kesalahan!</div>
                    <div class="toast-msg">{{ session('error') }}</div>
                </div>
                <button onclick="closeToast()" class="toast-close">
                    <i class="bi bi-x-lg" style="font-size:.8rem;"></i>
                </button>
                <div class="toast-progress toast-progress-error"></div>
            </div>
            @endif
        </div>

        <!-- ── Page Content ── -->
        <div class="content-area">
            @yield('content')
        </div>

        <!-- ── Footer ── -->
        <footer style="padding:1rem 2rem;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
            <span style="font-size:.72rem;color:var(--text-muted);">
                © {{ date('Y') }} BaKos — Cari Kos Tanpa Ribet
            </span>
            <span style="font-size:.72rem;color:var(--text-muted);display:flex;align-items:center;gap:4px;">
                Dibuat oleh mahasiswa Amik></i> di Manado
            </span>
        </footer>
    </div>

    <!-- ═══════════════════════════════════════════
         SCRIPTS
         ═══════════════════════════════════════════ -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ═══ Sidebar Toggle ═══
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').style.display = 'none';
        }

        // Close on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });

        // ═══ Toast ═══
        function closeToast() {
            const t = document.getElementById('toast');
            if (t) {
                t.classList.add('hide');
                setTimeout(() => t.remove(), 300);
            }
        }
        const toast = document.getElementById('toast');
        if (toast) setTimeout(closeToast, 5000);

        // ═══ Sidebar Link Ripple (Mouse tracking) ═══
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('mousemove', (e) => {
                const rect = link.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                link.style.setProperty('--mouse-x', x + '%');
                link.style.setProperty('--mouse-y', y + '%');
            });
        });

        // ═══ Stat Counter Animation ═══
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const target = parseInt(el.textContent.replace(/[^0-9]/g, ''));
                        if (!target || el.dataset.counted) return;
                        el.dataset.counted = 'true';

                        const suffix = el.textContent.replace(/[0-9.,]/g, '');
                        const duration = 800;
                        const start = performance.now();

                        function animate(now) {
                            const elapsed = now - start;
                            const progress = Math.min(elapsed / duration, 1);
                            const eased = 1 - Math.pow(1 - progress, 3);
                            const current = Math.floor(eased * target);
                            el.textContent = current.toLocaleString('id-ID') + suffix;
                            if (progress < 1) requestAnimationFrame(animate);
                        }
                        requestAnimationFrame(animate);
                    }
                });
            }, { threshold: 0.3 });

            document.querySelectorAll('.stat-number').forEach(el => observer.observe(el));
        });

        // ═══ Card hover tilt effect (subtle) ═══
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width;
                const y = (e.clientY - rect.top) / rect.height;
                const rotateX = (y - 0.5) * -4;
                const rotateY = (x - 0.5) * 4;
                card.style.transform = `translateY(-3px) perspective(600px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) perspective(600px) rotateX(0) rotateY(0)';
            });
        });
    </script>
    @stack('scripts')
</body>
</html>