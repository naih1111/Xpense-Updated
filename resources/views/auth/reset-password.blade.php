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

        .reset-container {
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

        .reset-card {
            background: white;
            border-radius: 24px;
            padding: 30px;
            width: 100%;
            max-width: 460px;
        }

        .reset-title {
            color: #0A1B3F;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
        }

        .reset-subtitle {
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

        .reset-button {
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

        .reset-button:hover {
            background: rgb(56, 91, 172);
        }

        .error-message {
            color: #DC2626;
            font-size: 14px;
            margin-top: 4px;
        }

        .form-input.error {
            border-color: #DC2626;
        }

        /* Add styles for password input container */
        .password-input-container {
            position: relative;
            width: 100%;
        }

        .password-input-container .form-input {
            padding-right: 45px; /* Make room for the icon */
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #64748B;
            transition: color 0.3s ease;
        }

        .password-toggle-btn:hover {
            color: #0A1B3F;
        }

        .password-toggle-btn:focus {
            outline: none;
        }

        .password-toggle-btn .material-icons {
            font-size: 20px;
        }

        @media (max-width: 768px) {
            .reset-container {
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

            .reset-card {
                padding: 32px;
            }

            .brand-name {
                font-size: 48px;
            }
        }
    </style>

    <div class="reset-container">
        <div class="brand-section">
            <img src="{{ asset('images/XPENSELosgz (2).png') }}" alt="Xpense Logo" class="brand-logo">
            <div class="brand-name">Xpense</div>
        </div>

        <div class="form-section">
            <div class="reset-card">
                <h2 class="reset-title">Reset Password</h2>
                <p class="reset-subtitle">Create a new password for your account</p>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email', $request->email) }}" 
                            required 
                            autofocus 
                            class="form-input @error('email') error @enderror"
                            placeholder="Enter your email">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <div class="password-input-container">
                            <input id="password" 
                                type="password" 
                                name="password" 
                                required 
                                class="form-input @error('password') error @enderror"
                                placeholder="Enter new password">
                            <button type="button" 
                                class="password-toggle-btn"
                                onclick="togglePasswordVisibility('password')"
                                title="Toggle password visibility">
                                <span class="material-icons">visibility_off</span>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="password-input-container">
                            <input id="password_confirmation" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                class="form-input @error('password_confirmation') error @enderror"
                                placeholder="Confirm new password">
                            <button type="button" 
                                class="password-toggle-btn"
                                onclick="togglePasswordVisibility('password_confirmation')"
                                title="Toggle password visibility">
                                <span class="material-icons">visibility_off</span>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="reset-button">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Password Toggle Script -->
    <script>
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('.material-icons');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility';
                button.title = 'Hide password';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility_off';
                button.title = 'Show password';
            }
        }
    </script>
</x-guest-layout> 