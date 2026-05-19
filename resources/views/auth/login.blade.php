<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Gym Management System</title>
    <style>
        /* Background */
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #e9ecef;
        }

        /* Center */
        .wrapper {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Card */
        .card {
            width: 360px;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
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
            margin: 10px 0 5px;
            font-weight: 600;
        }

        .subtitle {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* Toggle */
        .toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .toggle button {
            flex: 1;
            display: flex;
            align-items: center;       
            justify-content: center;   
            gap: 8px;
            padding: 12px 10px;  
            border-radius: 8px;
            border: none;
            background: #E6F1FF;
            cursor: pointer;
            font-weight: 700;
            font-size: 16px;  
            transition: all 0.3s ease;
            color: #0070FF;
        }

        .toggle button:hover {
            background: #B0D3FF;
            transform: translateY(-2px);
        }

        .toggle button strong {
            font-weight: 700;
            font-size: 16px;
        }

        .toggle .active {
            background: #1e73ff;
            color: white;
        }

        .toggle .active:hover {
            background: #0a5cd9;
        }

        /* Form */
        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .form-group label {
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

        /* Password container for show/hide */
        .password-container {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        .password-container input {
            flex: 1;
            border: none;
            outline: none;
        }

        .toggle-password {
            cursor: pointer;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            padding: 0;
        }

        .toggle-password:hover {
            color: #1e73ff;
        }

        /* Icons */
        .icon {
            color: #6c757d;
            font-size: 14px;
        }

        /* Button */
        .login-btn {
            width: 100%;
            padding: 12px;
            background: #1e73ff;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .login-btn:hover {
            background: #0a5cd9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 115, 255, 0.3);
        }

        /* Signup */
        .signup {
            margin-top: 15px;
            font-size: 13px;
        }

        .signup a {
            color: #1e73ff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .signup a:hover {
            text-decoration: underline;
        }

        /* Error messages - RED */
        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }

        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: left;
        }

        /* Hide class */
        .hidden {
            display: none;
        }

        /* ========== MOBILE ZOOM ADAPTATION ========== */
        /* When viewport width is <= 480px (mobile pov), zoom in the card */
        @media (max-width: 480px) {
            .wrapper {
                /* Ensure vertical centering remains */
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0 12px;
            }
            
            .card {
                /* Zoom effect: slightly larger scale and increased base width for readability */
                transform: scale(1.02);
                width: auto;
                max-width: 380px;
                min-width: 260px;
                width: 92%;
                padding: 28px 24px;
                /* Smooth transformation for subtle zoom-in effect */
                transition: transform 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
                /* Slight increase in shadow to enhance depth */
                box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            }
            
            /* Slight adjustment to body background for better mobile contrast, no design change just mobile comfort */
            body {
                background: #e9ecef;
            }
            
            /* Ensure all interactive elements remain fully tappable and no overflow */
            .toggle button {
                padding: 12px 8px;
                font-size: 15px;
            }
            
            /* Maintain original spacing; just ensure no text clipping */
            .input-box {
                padding: 10px 12px;
            }
            
            .login-btn {
                padding: 12px;
                font-size: 1rem;
            }
            
            /* Slight increase to margin for better touch spacing, still matches original aesthetics */
            .form-group {
                margin-bottom: 16px;
            }
            
            /* Preserve exact color schemes and border radius - only zoom and slight width adaptation */
            .card {
                border-radius: 20px;
            }
        }
        
        /* For devices that are extremely narrow (<= 360px) ensure no horizontal scroll & card remains zoomed but readable */
        @media (max-width: 360px) {
            .card {
                transform: scale(1.01);
                padding: 24px 20px;
                width: 94%;
            }
            
            .toggle button strong {
                font-size: 14px;
            }
            
            .toggle svg {
                width: 20px;
                height: 20px;
            }
        }
        
        /* Also for larger phones in landscape but still mobile, keep scale consistent */
        @media (min-width: 481px) and (max-width: 768px) {
            
            .card {
                transform: scale(1.01);
                width: 380px;
            }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="card">
        <!-- Logo -->
        <div class="logo">
            <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.596 12.768a2 2 0 1 0 2.829-2.829l-1.768-1.767a2 2 0 0 0 2.828-2.829l-2.828-2.828a2 2 0 0 0-2.829 2.828l-1.767-1.768a2 2 0 1 0-2.829 2.829z"/><path d="m2.5 21.5 1.4-1.4"/><path d="m20.1 3.9 1.4-1.4"/><path d="M5.343 21.485a2 2 0 1 0 2.829-2.828l1.767 1.768a2 2 0 1 0 2.829-2.829l-6.364-6.364a2 2 0 1 0-2.829 2.829l1.768 1.767a2 2 0 0 0-2.828 2.829z"/><path d="m9.6 14.4 4.8-4.8"/></svg></span>
        </div>

        <!-- Title -->
        <h2>Gym Management System</h2>
        <p class="subtitle">Sign in to your account</p>

        <!-- Toggle -->
        <div class="toggle">
            <button type="button" id="memberBtn" class="active" onclick="setRole('member', this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg> 
                <strong>Member</strong>
            </button>
            <button type="button" id="trainerBtn" onclick="setRole('trainer', this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                <strong>Trainer</strong>
            </button>
        </div>

        <!-- Session Status: static structure preserved - dynamic messages if any could be injected but keep as per Blade placeholders -->
        @if(session('status'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <!-- Error Messages - RED (Static demo simulation, but fully preserved structure) -->
        @if($errors->any())
            <div class="alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="role" id="role" value="member">

            <div class="form-group">
                <label>Email</label>
                <div class="input-box">
                    <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg></span>
                    <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-box">
                    <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                    <div class="password-container">
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <button class="login-btn">Sign in</button>

            <p id="signupText" class="signup">
                Don't have an account?
                <a href="{{ route('register') }}">Sign up</a>
            </p>
        </form>
    </div>
</div>

<script>
    // Preserved original role toggle logic with full compatibility
    function setRole(role, buttonElement) {
        // Set the hidden input value
        document.getElementById('role').value = role;
        
        // Get both buttons
        const memberBtn = document.getElementById('memberBtn');
        const trainerBtn = document.getElementById('trainerBtn');
        
        // Remove active class from both buttons
        if (memberBtn) memberBtn.classList.remove('active');
        if (trainerBtn) trainerBtn.classList.remove('active');
        
        // Add active class to the clicked button
        buttonElement.classList.add('active');
        
        // Show/hide signup link based on role (original behavior)
        const signupText = document.getElementById('signupText');
        if (role === 'member') {
            signupText.style.display = 'block';
        } else {
            signupText.style.display = 'none';
        }
    }
    
    // Toggle password visibility exactly as original, with eye/eye-slash switching
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.querySelector('.toggle-password');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            // Change icon to eye-slash (matching original icon style)
            if (toggleBtn) {
                toggleBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
            </svg>`;
            }
        } else {
            passwordInput.type = 'password';
            // Change icon back to eye (default)
            if (toggleBtn) {
                toggleBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>`;
            }
        }
    }
    
    // Ensure initial signup visibility based on member (active by default)
    document.addEventListener('DOMContentLoaded', function() {
        // Validate toggle states: default role is member, memberBtn active accordingly
        const roleInput = document.getElementById('role');
        if (roleInput && roleInput.value === 'member') {
            const memberBtn = document.getElementById('memberBtn');
            const trainerBtn = document.getElementById('trainerBtn');
            if (memberBtn && trainerBtn) {
                memberBtn.classList.add('active');
                trainerBtn.classList.remove('active');
            }
            const signupText = document.getElementById('signupText');
            if (signupText) signupText.style.display = 'block';
        }
        
       
    });
    
    // Additional small fix for devices with orientation change: ensure scaling smooth.
    window.addEventListener('resize', function() {
        
        if (window.innerWidth <= 480) {
            // nothing extra, just to preserve original padding etc.
            document.querySelectorAll('.card').forEach(card => {
                // ensure no inline style interferes with media query (just precaution)
                if (!card.style.transform) card.style.transform = '';
            });
        }
    });
</script>


</body>
</html>