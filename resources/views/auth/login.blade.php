<x-guest-layout>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        .login-container {
            width: 100%;
            height: 100vh;
            display: flex;
            background-color: #0A1B3F;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .brand-section {
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-name {
            color: white;
            font-size: 72px;
            font-weight: bold;
        }

        .form-section {
            width: 45%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            width: 100%;
            max-width: 460px;
        }

        .login-title {
            color: #0A1B3F;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 40px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            color: #0A1B3F;
            font-weight: 500;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #E1E5EE;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: #0A1B3F;
            outline: none;
        }

        .login-button {
            width: 100%;
            padding: 16px;
            background: #0A1B3F;
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            margin-top: 32px;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background: rgb(56, 91, 172);
        }

        .register-link {
            text-align: center;
            margin-top: 24px;
            color: #64748B;
            font-size: 18px;
        }

        .register-link a {
            color: #0A1B3F;
            text-decoration: none;
            font-weight: 500;
            margin-left: 4px;  
        }

        .error-message {
            color: #DC2626;
            font-size: 14px;
            margin-top: 4px;
        }

        .form-input.error {
            border-color: #DC2626;
        }

        .brand-logo {
            width: 140px; /* Adjust size */
            margin-bottom: 1px; /* Space between logo and brand name */
        }

        .remember-me-label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .remember-checkbox {
            width: 16px;
            height: 16px;
            border: 2px solid #E1E5EE;
            border-radius: 4px;
            margin-right: 8px;
            cursor: pointer;
        }

        .remember-text {
            color: #64748B;
            font-size: 16px;
        }

        .remember-checkbox:checked {
            background-color: #0A1B3F;
            border-color: #0A1B3F;
        }

        .forgot-password {
            text-align: center;
            margin-top: 24px;
        }

        .forgot-password a {
            color: #64748B;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #0A1B3F;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .brand-section {
                width: 100%;
                height: 30%;
            }

            .form-section {
                width: 100%;
                height: 70%;
            }

            .login-card {
                padding: 32px;
            }

            .brand-name {
                font-size: 48px;
            }

            
        }
    </style>

    <div class="login-container">
        <div class="brand-section">
        <img src="{{ asset('images/XPENSELosgz (2).png') }}" alt="Xpense Logo" class="brand-logo">
            <div class="brand-name">Xpense</div>
        </div>
        
        <div class="form-section">
            <div class="login-card">
                <h2 class="login-title">Login</h2>
                
                @if (session('success'))
                    <div style="background-color: #D1FAE5; color: #065F46; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                            class="form-input @error('email') error @enderror">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" name="password" required 
                            class="form-input @error('password') error @enderror">
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="remember-me-label">
                            <input type="checkbox" 
                                name="remember" 
                                class="remember-checkbox"
                            >
                            <span class="remember-text">Remember me</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="login-button">
                            Log in
                        </button>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="forgot-password">
                            <a href="{{ route('password.request') }}" class="text-sm text-gray-700 hover:text-gray-900">
                                Forgot your password?
                            </a>
                        </div>
                    @endif
                </form>

                <div class="register-link">
                    <span>Don't have an account?</span>
                    <a href="{{ route('register') }}">Sign up</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
