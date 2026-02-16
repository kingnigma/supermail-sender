<x-guest-layout>
    <div class="auth-form-wrapper">
        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button id="loginTab" class="tab-btn active" onclick="switchTab('login')">
                <i class="bi bi-box-arrow-in-right"></i> {{ __('Sign In') }}
            </button>
            <button id="registerTab" class="tab-btn" onclick="switchTab('register')">
                <i class="bi bi-person-plus"></i> {{ __('Create Account') }}
            </button>
        </div>

        <!-- Login Form -->
        <div id="loginForm" class="tab-content active">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Please correct the errors below.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-input-wrapper">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <div style="position: relative;">
                        <span class="form-input-icon">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input id="email" type="email" name="email" class="form-control with-icon" 
                            value="{{ old('email') }}" required autofocus autocomplete="username" 
                            placeholder="you@example.com" />
                    </div>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-input-wrapper">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <div style="position: relative;">
                        <span class="form-input-icon">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input id="password" type="password" name="password" class="form-control with-icon" 
                            required autocomplete="current-password" placeholder="Enter your password" />
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" class="form-checkbox" name="remember">
                        <span style="font-size: 14px; color: #666;">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="forgot-password-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                </div>

                <!-- Sign In Button -->
                <button type="submit" class="btn-primary-gradient">
                    <i class="bi bi-box-arrow-in-right"></i> {{ __('Sign In') }}
                </button>
            </form>

            <!-- Sign Up Link -->
            <p class="text-center-secondary">
                {{ __("Don't have an account?") }}
                <button type="button" onclick="switchTab('register')" class="btn-secondary-link">
                    {{ __('Create one now') }}
                </button>
            </p>
        </div>

        <!-- Register Form -->
        <div id="registerForm" class="tab-content hidden">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Please correct the errors below.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-input-wrapper">
                    <label for="name" class="form-label">{{ __('Full Name') }}</label>
                    <div style="position: relative;">
                        <span class="form-input-icon">
                            <i class="bi bi-person"></i>
                        </span>
                        <input id="name" type="text" name="name" class="form-control with-icon" 
                            value="{{ old('name') }}" required autofocus autocomplete="name" 
                            placeholder="John Doe" />
                    </div>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="form-input-wrapper">
                    <label for="email_register" class="form-label">{{ __('Email Address') }}</label>
                    <div style="position: relative;">
                        <span class="form-input-icon">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input id="email_register" type="email" name="email" class="form-control with-icon" 
                            value="{{ old('email') }}" required autocomplete="username" 
                            placeholder="you@example.com" />
                    </div>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-input-wrapper">
                    <label for="password_register" class="form-label">{{ __('Password') }}</label>
                    <div style="position: relative;">
                        <span class="form-input-icon">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input id="password_register" type="password" name="password" class="form-control with-icon" 
                            required autocomplete="new-password" placeholder="Create a strong password" />
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-input-wrapper">
                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                    <div style="position: relative;">
                        <span class="form-input-icon">
                            <i class="bi bi-lock-check"></i>
                        </span>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control with-icon" 
                            required autocomplete="new-password" placeholder="Confirm your password" />
                    </div>
                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Terms & Conditions -->
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <input id="terms" type="checkbox" class="form-checkbox" name="terms" required>
                    <label for="terms" style="margin: 0; font-size: 14px; color: #666;">
                        {{ __('I agree to the') }}
                        <a href="#" style="color: #667eea; text-decoration: none;">{{ __('Terms & Conditions') }}</a>
                    </label>
                </div>

                <!-- Sign Up Button -->
                <button type="submit" class="btn-primary-gradient" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="bi bi-person-plus"></i> {{ __('Create Account') }}
                </button>
            </form>

            <!-- Sign In Link -->
            <p class="text-center-secondary">
                {{ __('Already have an account?') }}
                <button type="button" onclick="switchTab('login')" class="btn-secondary-link">
                    {{ __('Sign in here') }}
                </button>
            </p>
        </div>
    </div>

    <!-- Tab Switching Script -->
    <script>
        function switchTab(tab) {
            // Hide all forms and remove active state
            document.getElementById('loginForm').classList.add('hidden');
            document.getElementById('registerForm').classList.add('hidden');
            document.getElementById('loginTab').classList.remove('active');
            document.getElementById('registerTab').classList.remove('active');

            // Show selected tab and mark as active
            if (tab === 'login') {
                document.getElementById('loginForm').classList.remove('hidden');
                document.getElementById('loginTab').classList.add('active');
            } else {
                document.getElementById('registerForm').classList.remove('hidden');
                document.getElementById('registerTab').classList.add('active');
            }
        }

        // Check if there are errors to determine which tab to show
        document.addEventListener('DOMContentLoaded', function() {
            const hasRegisterErrors = !!document.querySelector('#registerForm .error-message');
            if (hasRegisterErrors) {
                switchTab('register');
            }
        });
    </script>
</x-guest-layout>
