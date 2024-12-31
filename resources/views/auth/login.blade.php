<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        .auth-container {
            position: relative;
            width: 100%;
            max-width: 1200px;
            min-height: 600px; /* Changed from fixed height to min-height */
            background:rgb(255, 255, 255);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            display: flex;
            flex-wrap: wrap; /* Added to handle responsive layout better */
            margin: 2rem auto; /* Added margin for better spacing */
        }

        .content-side {
            width: 50%;
            padding: 40px;
            transition: all 0.5s ease;
            display: flex;
            flex-direction: column;
        }

        .illustration-side {
            width: 50%;
            background: linear-gradient(135deg, #eab0d2 0%,rgb(221, 88, 166) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
            transition: all 0.5s ease;
            position: sticky;
            top: 0;
           
        }

        /* Update media query for better mobile responsiveness */
        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
                margin: 1rem;
            }

            .content-side,
            .illustration-side {
                width: 100%;
            }

            .illustration-side {
                min-height: 200px; /* Reduced height on mobile */
                position: relative;
            }

            .auth-form {
                padding-bottom: 2rem; /* Add some padding at the bottom */
            }
        }

        /* Add some spacing between form groups */
        .form-group {
            margin-bottom: 1.5rem; /* Increased from 1rem */
        }

        /* Ensure the body has proper padding */
        body {
            min-height: 100vh;
            background:rgb(255, 255, 255);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem; /* Added padding */
        }

      

        .illustration-side::before {
            content: '';
            position: absolute;
            width: 200%;
      
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z'/%3E%3Cpath d='M6 5V0H5v5H0v1h5v94h1V6h94V5H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.1;
            animation: animatePattern 30s linear infinite;
        }

        @keyframes animatePattern {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .auth-form {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .auth-form h1 {
            color: #000;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            color: #94a3b8;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background:rgb(255, 255, 255);
            border: 1px solid #000;
            border-radius: 0.5rem;
            color: #000;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #eab0d2;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1rem;
        }

        .btn {
            width: 100%;
            padding: 0.75rem 1.5rem;
            background: #eab0d2;
            color: #fff;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: # #eab0d2;
        }

        .btn.btn-outline {
            background: transparent;
            border: 1px solid #eab0d2;
            color: #eab0d2;
        }

        .btn.btn-outline:hover {
            background: #eab0d2;
            color: #fff;
        }

        .social-buttons {
            display: flex;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .social-btn {
            flex: 1;
            padding: 0.75rem;
            background: #2d3748;
            border: none;
            border-radius: 0.5rem;
            color: #fff;
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .social-btn:hover {
            background: #4a5568;
        }

        .form-footer {
            margin-top: 1.5rem;
            text-align: center;
            color: #94a3b8;
        }

        .form-footer a {
            color: #eab0d2;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .file-input-wrapper {
            position: relative;
            width: 100%;
            height: 100px;
            border: 2px dashed #4a5568;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input-wrapper:hover {
            border-color: #eab0d2;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-input-wrapper .placeholder {
            color: #94a3b8;
            text-align: center;
        }

        .switch-form-button {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background: transparent;
            border: 2px solid #fff;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .switch-form-button:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .illustration-content {
            text-align: center;
            color: #fff;
            z-index: 1;
        }

        .illustration-content h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .illustration-content p {
            font-size: 1.125rem;
            opacity: 0.9;
            max-width: 400px;
            margin: 0 auto;
        }

      
    </style>
</head>
<body>
    <div class="auth-container" id="authContainer">
        <!-- Login Form -->
        <div class="content-side" id="loginForm">
            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf
                <h1>Welcome back</h1>
          

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" class="form-input" required value="{{ old('email') }}">
                    <x-input-error :messages="$errors->get('email')" class="error-message" />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                    <x-input-error :messages="$errors->get('password')" class="error-message" />
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="form-checkbox" style="margin-right: 0.5rem;">
                            <span class="text-sm text-gray-400">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="color: #eab0d2;
    text-decoration: none;">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn">Sign in</button>

                <div class="form-footer">
                    <p>Don't have an account? <a href="#" id="showRegisterForm">Create account</a></p>
                </div>
            </form>
        </div>

        <!-- Register Form -->
        <div class="content-side" id="registerForm" style="display: none;">
            <form method="POST" action="{{ route('register') }}" class="auth-form" enctype="multipart/form-data">
                @csrf
                <h1>Create account</h1>

                <div class="form-group">
                    <label for="reg-name">Full name</label>
                    <input type="text" id="reg-name" name="name" class="form-input" required value="{{ old('name') }}">
                    <x-input-error :messages="$errors->get('name')" class="error-message" />
                </div>

                <div class="form-group">
                    <label for="reg-email">Email address</label>
                    <input type="email" id="reg-email" name="email" class="form-input" required value="{{ old('email') }}">
                    <x-input-error :messages="$errors->get('email')" class="error-message" />
                </div>

                <div class="form-group">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" name="password" class="form-input" required>
                    <x-input-error :messages="$errors->get('password')" class="error-message" />
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" class="form-input" required value="{{ old('address') }}">
                    <x-input-error :messages="$errors->get('address')" class="error-message" />
                </div>

                <div class="form-group">
                    <label for="phone">Phone number</label>
                    <input type="text" id="phone" name="phone" class="form-input" required value="{{ old('phone') }}">
                    <x-input-error :messages="$errors->get('phone')" class="error-message" />
                </div>

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" class="form-input form-select" required>
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="error-message" />
                </div>

                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" class="form-input" required value="{{ old('age') }}">
                    <x-input-error :messages="$errors->get('age')" class="error-message" />
                </div>

                <div class="form-group">
                    <label for="image">Profile Image</label>
                    <div class="file-input-wrapper">
                        <input type="file" id="image" name="image" accept="image/*" required>
                        <div class="placeholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload image</p>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('image')" class="error-message" />
                </div>

                <div class="form-group">
                    <label for="user_role">User Role</label>
                    <select id="user_role" name="user_role" class="form-input form-select" required>
                        <option value="">Select role</option>
                        <option value="customer" {{ old('user_role') == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="admin" {{ old('user_role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <x-input-error :messages="$errors->get('user_role')" class="error-message" />
                </div>

                <button type="submit" class="btn">Create account</button>

                <div class="form-footer">
                    <p>Already have an account? <a href="#" id="showLoginForm">Sign in</a></p>
                </div>
            </form>
        </div>

        <!-- Illustration Side -->
        <div class="illustration-side">
            <div class="illustration-content" id="illustrationContent">
                <h2>ALMA</h2>
                <p>Enter your personal details and start your journey with us</p>
            </div>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const showRegisterFormBtn = document.getElementById('showRegisterForm');
        const showLoginFormBtn = document.getElementById('showLoginForm');
        const illustrationContent = document.getElementById('illustrationContent');

        showRegisterFormBtn.addEventListener('click', (e) => {
            e.preventDefault();
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
            illustrationContent.innerHTML = `
                <h2>Hello, Friend!</h2>
                <p>Fill in your information and start your journey with us</p>
            `;
        });

        showLoginFormBtn.addEventListener('click', (e) => {
            e.preventDefault();
            registerForm.style.display = 'none';
            loginForm.style.display = 'block';
            illustrationContent.innerHTML = `
                <h2>Welcome Back!</h2>
                <p>Enter your personal details and start your journey with us</p>
            `;
        });

        // File input preview
        const fileInput = document.getElementById('image');
        const placeholder = document.querySelector('.placeholder');

        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                placeholder.innerHTML = `
                    <i class="fas fa-check"></i>
                    <p>${fileName}</p>
                `;
            }
        });
    </script>
</body>
</html>