<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

        <!-- Tailwind CSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Custom utility classes for auth pages */
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .auth-container {
                background: white;
                border-radius: 12px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                overflow: hidden;
                max-width: 450px;
                width: 100%;
                margin: 20px;
            }

            .auth-header {
                text-align: center;
                padding: 40px 20px 20px;
            }

            .auth-logo {
                max-width: 200px;
                height: auto;
                margin-bottom: 20px;
            }

            .tab-navigation {
                display: flex;
                border-bottom: 2px solid #e0e0e0;
                margin-bottom: 30px;
            }

            .tab-btn {
                flex: 1;
                padding: 15px 20px;
                text-align: center;
                font-weight: 600;
                cursor: pointer;
                border: none;
                background: transparent;
                color: #999;
                transition: all 0.3s ease;
                position: relative;
            }

            .tab-btn.active {
                color: #667eea;
            }

            .tab-btn.active::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 0;
                right: 0;
                height: 2px;
                background: #667eea;
            }

            .form-control {
                border: 1px solid #ddd;
                border-radius: 6px;
                padding: 10px 15px;
                font-size: 14px;
                transition: all 0.3s ease;
            }

            .form-control:focus {
                background-color: white;
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            }

            .form-input-wrapper {
                position: relative;
                margin-bottom: 20px;
            }

            .form-input-icon {
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: #999;
            }

            .form-control.with-icon {
                padding-left: 40px;
            }

            .btn-primary-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                color: white;
                font-weight: 600;
                padding: 12px 20px;
                border-radius: 6px;
                width: 100%;
                cursor: pointer;
                transition: all 0.3s ease;
                margin-top: 10px;
            }

            .btn-primary-gradient:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
                color: white;
                text-decoration: none;
            }

            .btn-secondary-link {
                color: #667eea;
                background: none;
                border: none;
                cursor: pointer;
                font-weight: 600;
                padding: 0;
                margin-left: 5px;
                transition: color 0.3s ease;
            }

            .btn-secondary-link:hover {
                color: #764ba2;
                text-decoration: underline;
            }

            .text-center-secondary {
                text-align: center;
                margin-top: 20px;
                color: #666;
                font-size: 14px;
            }

            .forgot-password-link {
                color: #667eea;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.3s ease;
            }

            .forgot-password-link:hover {
                color: #764ba2;
                text-decoration: underline;
            }

            .form-checkbox {
                width: 18px;
                height: 18px;
                cursor: pointer;
                accent-color: #667eea;
                margin-right: 8px;
            }

            .tab-content {
                animation: fadeIn 0.3s ease-in;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(5px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .hidden {
                display: none !important;
            }

            .error-message {
                color: #dc3545;
                font-size: 13px;
                margin-top: 5px;
            }

            .form-label {
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-header">
                <img src="{{ url('images/mailer-logo.png') }}" alt="Logo" class="auth-logo">
            </div>

            <div style="padding: 0 30px 30px;">
                {{ $slot }}
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
