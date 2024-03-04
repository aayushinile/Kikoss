<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kikos Tour Soahu</title>
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/auth.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/admin-css/responsive.css') }}">
    <script src="{{ assets('assets/admin-js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/admin-plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
</head>

<body>
    <div class="auth-section auth-height">
        <div class="auth-bg-video">
            <img src="{{ assets('assets/admin-images/bg.webp') }}" id="background-video">
        </div>
        <div class="auth-content-card">
            <div class="container">
                <div class="auth-card">
                    <div class="row justify-content-center">
                        <div class="col-md-12 auth-form-info">
                            <div class="auth-form">
                                <div class="brand-logo">
                                    <img src="{{ assets('assets/admin-images/logo.svg') }}" alt="logo">
                                </div>
                                <h2>Admin Login</h2>
                                <p>YOUR GUIDE TO ADVENTURE ON O'AHU</p>
                                <form method="POST" action="{{ route('login') }}" class="pt-4">
                                    @csrf
                                    <div class="form-group">
                                        <input type="email" id="email"
                                            class="form-control
                                            @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" required autocomplete="email"
                                            autofocus placeholder="Email Address">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="current-password"placeholder="Password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button class="auth-form-btn"type="sumbit">Login</button>
                                    </div>

                                    <div class="mt-1 forgotpsw-text">
                                        <a href="{{ route('password.request') }}">Forgot Password</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
