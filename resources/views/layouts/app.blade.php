<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8fafc;
            font-family: 'Figtree', sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
            z-index: 1000;
        }

        .sidebar .p-3 {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .sidebar h4 {
            color: white;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.25);
            color: white !important;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar hr {
            border-color: rgba(255, 255, 255, 0.2);
            margin: 20px 0;
        }

        .sidebar .text-danger {
            color: #ff6b6b !important;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            padding: 20px 30px;
        }

        /* Header Section */
        .page-header {
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h2 {
            color: #2d3748;
            font-weight: 700;
            font-size: 1.75rem;
            margin: 0;
        }

        /* Dropdown */
        .dropdown-toggle::after {
            border: none;
        }

        .dropdown-toggle {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none !important;
            color: white !important;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .dropdown-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .dropdown-menu {
            border: none;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            margin-top: 10px;
        }

        .dropdown-item {
            color: #4a5568;
            font-weight: 500;
            padding: 10px 20px;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: #f0f4ff;
            color: #667eea;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 20px;
            font-weight: 600;
        }

        .card-body {
            padding: 25px;
        }

        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid #667eea;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }

        .stat-card.primary {
            border-left-color: #667eea;
        }

        .stat-card.success {
            border-left-color: #10b981;
        }

        .stat-card.warning {
            border-left-color: #f59e0b;
        }

        .stat-card.info {
            border-left-color: #3b82f6;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #718096;
            font-weight: 600;
            font-size: 0.95rem;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-outline-primary {
            color: #667eea;
            border-color: #667eea;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: #667eea;
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-sm {
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        /* Table Styling */
        .table {
            color: #4a5568;
        }

        .table thead th {
            background: #f7fafc;
            color: #2d3748;
            font-weight: 700;
            border: none;
            padding: 15px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #e2e8f0;
        }

        .table tbody tr:hover {
            background: #f7fafc;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
        }

        /* Badge */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .badge.bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        }

        /* Alert */
        .alert {
            border: none;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }

        .alert-danger {
            background: #fee2e2;
            color: #7f1d1d;
        }

        .alert-warning {
            background: #fef3c7;
            color: #78350f;
        }

        /* Form Elements */
        .form-control, .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 20px;
        }

        .modal-header .modal-title {
            font-weight: 700;
            font-size: 1.2rem;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: slideIn 0.3s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-header h2 {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="p-3">
                <div>
                    <h4 class="text-center">{{ config('app.name') }}</h4>
                    <hr>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('send_email') ? 'active' : '' }}" href="/send_email">
                                <i class="bi bi-send me-2"></i> Create Campaign
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('campaigns*') ? 'active' : '' }}" href="/campaigns">
                                <i class="bi bi-clock-history me-2"></i> My Campaigns
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="/contact">
                                <i class="bi bi-people me-2"></i> Contacts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('message-templates') ? 'active' : '' }}" href="/message-templates">
                                <i class="bi bi-envelope me-2"></i> Email Templates
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('invoice-templates') ? 'active' : '' }}" href="/invoice-templates">
                                <i class="bi bi-file-earmark-text me-2"></i> Invoice Templates
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('settings/email-api') ? 'active' : '' }}" href="{{ route('settings.email-api') }}">
                                <i class="bi bi-gear me-2"></i> Settings
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Logout Form at Bottom -->
                <div class="mt-auto">
                    <hr>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link text-danger w-100 text-start bg-transparent border-0">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="main-content flex-grow-1">
            <!-- Page Header -->
            <div class="page-header">
                <h2>@yield('title', 'Dashboard')</h2>
                <div class="btn-toolbar">
                    @auth
                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-2"></i>{{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                        </ul>
                    </div>
                    @endauth
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>