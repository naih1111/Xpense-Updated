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

        .forgot-container {
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

        .brand-logo {
            width: 140px;
            margin-bottom: 1px;
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

        .forgot-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            width: 100%;
            max-width: 460px;
        }

        .forgot-title {
            color: #0A1B3F;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
        }

        .forgot-subtitle {
            color: #64748B;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
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

        .forgot-button {
            width: 100%;
            padding: 16px;
            background: #0A1B3F;
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .forgot-button:hover {
            background:rgb(56, 91, 172);
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            color: #64748B;
        }

        .login-link a {
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

        .status-message {
            background-color: #F0FDF4;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .forgot-container {
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

            .forgot-card {
                padding: 32px;
            }

            .brand-name {
                font-size: 48px;
            }
        }
    </style>

    <div class="forgot-container">
        <div class="brand-section">
            <img src="{{ asset('images/XPENSELosgz (2).png') }}" alt="Xpense Logo" class="brand-logo">
            <div class="brand-name">Xpense</div>
        </div>

        <div class="form-section">
            <div class="forgot-card">
                <h2 class="forgot-title">Forgot Password</h2>
                <p class="forgot-subtitle">Enter your email and we'll send you a link to reset your password</p>

                @if (session('status'))
                    <div class="status-message">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            class="form-input @error('email') error @enderror"
                            placeholder="Enter your email">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="forgot-button">
                        Send Reset Link
                    </button>
                </form>

                <div class="login-link">
                    <span>Remember your password?</span>
                    <a href="{{ route('login') }}">Back to login</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>