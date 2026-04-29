<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Gym Management System</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #e9ecef;
        }
        .wrapper {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            width: 360px;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
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
        h2 {
            margin: 10px 0 5px;
            font-weight: 600;
        }
        .subtitle {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .admin-badge {
            display: inline-block;
            background: #1e73ff;
            color: white;
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 15px;
        }
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
        .icon {
            color: #6c757d;
            font-size: 14px;
        }
        .forgot {
            text-align: right;
            font-size: 12px;
            margin-bottom: 15px;
        }
        .forgot a {
            color: #1e73ff;
            text-decoration: none;
        }
        .forgot a:hover {
            text-decoration: underline;
        }
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
        }
        .login-btn:hover {
            background: #0a5cd9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 115, 255, 0.3);
        }
        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="card">
        <div class="logo">
            <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.596 12.768a2 2 0 1 0 2.829-2.829l-1.768-1.767a2 2 0 0 0 2.828-2.829l-2.828-2.828a2 2 0 0 0-2.829 2.828l-1.767-1.768a2 2 0 1 0-2.829 2.829z"/><path d="m2.5 21.5 1.4-1.4"/><path d="m20.1 3.9 1.4-1.4"/><path d="M5.343 21.485a2 2 0 1 0 2.829-2.828l1.767 1.768a2 2 0 1 0 2.829-2.829l-6.364-6.364a2 2 0 1 0-2.829 2.829l1.768 1.767a2 2 0 0 0-2.828 2.829z"/><path d="m9.6 14.4 4.8-4.8"/></svg></span>
        </div>
        <h2>Admin Portal</h2>
        <p class="subtitle">Sign in to admin dashboard</p>
        <div class="admin-badge">Administrator Access</div>

        @if ($errors->any())
            <div class="mb-4">
                @foreach ($errors->all() as $error)
                    <div class="error-message">{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
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
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
            </div>
            <div class="forgot">
                <a href="#">Forgot Password?</a>
            </div>
            <button class="login-btn">Sign in as Admin</button>
        </form>
    </div>
</div>

</body>
</html>