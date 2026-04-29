    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #e9ecef;
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            width: 700px;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        /* Back */
        .back {
            text-align: left;
            margin-bottom: 10px;
        }

        .back a {
            text-decoration: none;
            color: #1e73ff;
            transition: all 0.3s ease;
        }

        .back a:hover {
            text-decoration: underline;
        }

        /* Logo */
        .logo {
            width: 60px;
            height: 60px;
            background: #1e73ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 32px;
        }

        .logo svg {
            transform: scale(1.3);  
        }

        /* Title */
        h2 {
            text-align: center;
            margin: 10px 0 5px;
            font-weight: 600;
        }

        .subtitle {
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* Rows */
        .row {
            display: flex;
            gap: 15px;
        }

        /* Form */
        .form-group {
            flex: 1;
            margin-bottom: 15px;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        /* Input with icon */
        .input-box {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 10px;
        }

        .input-box input {
            border: none;
            outline: none;
            width: 100%;
        }

        /* Icons */
        .icon {
            color: #6c757d;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 12px;
            background: #1e73ff;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #0a5cd9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 115, 255, 0.3);
        }

        .bottom-text {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
        }

        .bottom-text a {
            color: #1e73ff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .bottom-text a:hover {
            text-decoration: underline;
        }

        .row:has(.form-group:only-child) {
            justify-content: left;
        }

        .row:has(.form-group:only-child) .form-group {
            max-width: 50%;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>

    <div class="wrapper">
        <div class="card">
            <!-- Back -->
            <div class="back">
                <a href="{{ route('login') }}">← Back</a>
            </div>

            <!-- Logo -->
            <div class="logo">
                <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.596 12.768a2 2 0 1 0 2.829-2.829l-1.768-1.767a2 2 0 0 0 2.828-2.829l-2.828-2.828a2 2 0 0 0-2.829 2.828l-1.767-1.768a2 2 0 1 0-2.829 2.829z"/><path d="m2.5 21.5 1.4-1.4"/><path d="m20.1 3.9 1.4-1.4"/><path d="M5.343 21.485a2 2 0 1 0 2.829-2.828l1.767 1.768a2 2 0 1 0 2.829-2.829l-6.364-6.364a2 2 0 1 0-2.829 2.829l1.768 1.767a2 2 0 0 0-2.828 2.829z"/><path d="m9.6 14.4 4.8-4.8"/></svg></span>
            </div>

            <h2>Create Your Account</h2>
            <p class="subtitle">Join our gym community today</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- ROW 1 -->
                <div class="row">
                    <div class="form-group">
                        <label>First Name</label>
                        <div class="input-box">
                            <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 21a8 8 0 0 1 10.821-7.487"/><path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><circle cx="10" cy="8" r="5"/></svg></span>
                                <input type="text" name="first_name" placeholder="Enter your first name" value="{{ old('first_name') }}" required>
                            </div>
                            @error('first_name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <div class="input-box">
                            <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
                                <input type="text" name="phone" placeholder="Enter your phone number" value="{{ old('phone') }}" required>
                            </div>
                            @error('phone')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                </div>

                <!-- ROW 2 -->
                <div class="row">
                    <div class="form-group">
                        <label>Middle Name</label>
                        <div class="input-box">
                            <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 21a8 8 0 0 1 10.821-7.487"/><path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><circle cx="10" cy="8" r="5"/></svg></span>
                                <input type="text" name="middle_name" placeholder="Enter your middle name" value="{{ old('middle_name') }}">
                            </div>
                            @error('middle_name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-box">
                            <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg></span>
                                <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                </div>

                <!-- ROW 3 -->
                <div class="row">
                    <div class="form-group">
                        <label>Last Name</label>
                        <div class="input-box">
                            <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 21a8 8 0 0 1 10.821-7.487"/><path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><circle cx="10" cy="8" r="5"/></svg></span>
                                <input type="text" name="last_name" placeholder="Enter your last name" value="{{ old('last_name') }}" required>
                            </div>
                            @error('last_name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                </div>

                <!-- PASSWORD -->
                <div class="row">
                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-box">
                            <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                                <input type="password" name="password" placeholder="Enter your password" required>
                            </div>
                            @error('password')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div class="input-box">
                            <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                                <input type="password" name="password_confirmation" placeholder="Confirm your password" required>
                            </div>
                        </div>
                </div>

                <!-- BUTTON -->
                <button class="btn">Sign up</button>

                <p class="bottom-text">
                    Already have an account?
                    <a href="{{ route('login') }}">Sign in</a>
                </p>

            </form>
        </div>
    </div>
