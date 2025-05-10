<x-guest-layout>
    <style>
        /* Copy the same style from your login page */
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

        .register-container {
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
            margin-bottom: 5px;
        }

        .brand-name {
            color: white;
            font-size: 72px;
            font-weight: bold;
        }

        .form-section {
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-card {
            background: white;
            border-radius: 26px;
            padding: 25px;
            width: 100%;
            max-width: 480px;
        }

        .register-title {
            color: #0A1B3F;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 40px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 5px;
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

        .register-button {
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

        .register-button:disabled {
            background: #64748B;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .register-button:hover:not(:disabled) {
            background: rgb(56, 91, 172);
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
            color: #EF4444;
            font-size: 14px;
            margin-top: 4px;
        }

        .name-fields {
            display: flex;
            gap: 16px;
        }

        .name-fields .form-group {
            flex: 1;
        }

        @media (max-width: 768px) {
            .register-container {
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

            .register-card {
                padding: 32px;
            }

            .brand-name {
                font-size: 48px;
            }
        }
    </style>

    <div class="register-container">
        <div class="brand-section">
            <img src="{{ asset('images/XPENSELosgz (2).png') }}" alt="Xpense Logo" class="brand-logo">
            <div class="brand-name">Xpense</div>
        </div>

        <div class="form-section">
            <div class="register-card">
                <h2 class="register-title">Sign Up</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="name-fields">
                        <div class="form-group">
                            <label for="first_name" class="form-label">First Name</label>
                            <input id="first_name" 
                                class="form-input" 
                                type="text" 
                                name="first_name" 
                                value="{{ old('first_name') }}" 
                                required 
                                autofocus 
                                placeholder="First Name" />
                            <x-input-error :messages="$errors->get('first_name')" class="error-message" />
                        </div>

                        <div class="form-group">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input id="last_name" 
                                class="form-input" 
                                type="text" 
                                name="last_name" 
                                value="{{ old('last_name') }}" 
                                required 
                                placeholder="Last Name" />
                            <x-input-error :messages="$errors->get('last_name')" class="error-message" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" 
                            class="form-input" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            placeholder="Enter your email" />
                        <x-input-error :messages="$errors->get('email')" class="error-message" />
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input id="phone" 
                            class="form-input" 
                            type="tel" 
                            name="phone" 
                            value="{{ old('phone') }}" 
                            required 
                            placeholder="Enter your phone number" />
                        <x-input-error :messages="$errors->get('phone')" class="error-message" />
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" 
                            class="form-input" 
                            type="password" 
                            name="password" 
                            required 
                            placeholder="Create a password" />
                        <x-input-error :messages="$errors->get('password')" class="error-message" />
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" 
                            class="form-input" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            placeholder="Confirm your password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="error-message" />
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label class="form-label" style="display: flex; align-items: center; font-weight: normal;">
                            <input type="checkbox" 
                                   name="terms" 
                                   id="terms"
                                   required
                                   style="width: 18px; height: 18px; margin-right: 8px;">
                            By signing up you agree to our 
                            <a href="#" id="view-terms-link" style="color: #0A1B3F; text-decoration: underline; margin-left: 4px;">Terms and Conditions</a>
                        </label>
                        <x-input-error :messages="$errors->get('terms')" class="error-message" />
                    </div>

                    <button type="submit" class="register-button" id="create-account-button">
                        Create Account
                    </button>

                   <!-- Terms and Conditions Modal -->
    <div id="termsModal" style="display: none; position: fixed; top: 0; left: 0; 
        width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); 
        justify-content: center; align-items: center; z-index: 1000;">

        <div style="background: white; padding: 40px; border-radius: 26px; width: 90%; max-width: 640px; max-height: 80vh; overflow-y: auto; position: relative;">
            <!-- Add X button -->
            <button id="closeModal" style="position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 24px; cursor: pointer; color: #0A1B3F;">
                ✕
            </button>

            <h2 style="color: #0A1B3F; font-size: 32px; font-weight: 700; margin-bottom: 20px; text-align: center;">Terms and Conditions</h2>

            <div id="termsContent" style="overflow-y: auto; max-height: 60vh; padding-right: 10px;">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, nisl vel tincidunt luctus, nunc sapien convallis augue, vitae scelerisque quam nisl at libero. Vivamus nec erat eu leo fermentum hendrerit.</p>
            
            <h3 style="color: #0A1B3F; font-size: 20px; font-weight: 600; margin-top: 24px;">1. Acceptance of Terms</h3>
            <p>By accessing or using this application, you agree to be bound by these terms and conditions. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor accumsan tincidunt.</p>

            <h3 style="color: #0A1B3F; font-size: 20px;  font-weight: 600; margin-top: 24px;">2. Use of the Service</h3>
            <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>

            <h3 style="color: #0A1B3F; font-size: 20px; font-weight: 600; margin-top: 24px;">3. User Responsibilities</h3>
            <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Nulla quis lorem ut libero malesuada feugiat.</p>

            <h3 style="color: #0A1B3F; font-size: 20px; font-weight: 600; margin-top: 24px;">4. Intellectual Property</h3>
            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.</p>

            <h3 style="color: #0A1B3F; font-size: 20px; font-weight: 600; margin-top: 24px;">5. Termination</h3>
            <p>We reserve the right to suspend or terminate your access to the service at any time, without notice, for conduct that we believe violates these Terms. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

            <h3 style="color: #0A1B3F; font-size: 20px; font-weight: 600; margin-top: 24px;">6. Limitation of Liability</h3>
            <p>In no event shall we be liable for any indirect, incidental, special, or consequential damages. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <h3 style="color: #0A1B3F; font-size: 20px; font-weight: 600; margin-top: 24px;">7. Governing Law</h3>
            <p>These Terms shall be governed and construed in accordance with the laws of [Insert Jurisdiction]. Nulla porttitor accumsan tincidunt.</p>
        </div>
    </div>
</div>

<!-- Modal Script -->
<script>
    const termsCheckbox = document.getElementById('terms');
    const submitButton = document.getElementById('create-account-button');
    const viewTermsLink = document.getElementById('view-terms-link');
    const termsModal = document.getElementById('termsModal');
    const closeModal = document.getElementById('closeModal');
    const form = document.querySelector('form');

    // Enable or disable the button
    termsCheckbox.addEventListener('change', function () {
        submitButton.disabled = !this.checked;
    });

    // Form submission handler
    form.addEventListener('submit', function(e) {
        if (!termsCheckbox.checked) {
            e.preventDefault();
            alert('Please accept the terms and conditions to continue.');
        }
    });

    // Open the modal
    viewTermsLink.addEventListener('click', function (e) {
        e.preventDefault();
        termsModal.style.display = 'flex';
    });

    // Close the modal
    closeModal.addEventListener('click', function () {
        termsModal.style.display = 'none';
    });

    // Close modal if clicking outside the modal box
    window.addEventListener('click', function(e) {
        if (e.target === termsModal) {
            termsModal.style.display = 'none';
        }
    });
</script>

         </form>

                <div class="login-link">
                    <span>Already have an account?</span>
                    <a href="{{ route('login') }}">Log In</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
