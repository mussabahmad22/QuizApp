<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>QuizApp | Log in</title>

        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

        <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">

        <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">

        <link rel="stylesheet" href="../../dist/css/adminlte.min.css?v=3.2.0">
    </head>

    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{url('/register')}}"><b>Quiz</b>App</a>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <div class="input-group mb-3">
                                <input id="email" type="email" class="form-control" name="email" :value="old('email')"
                                    placeholder="Email" autofocus />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" id="password" class="form-control" placeholder="Password"
                                    name="password" autocomplete="current-password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-dark btn-block">Sign In</button>
                                    </div>
                                    <div class="icheck-primary col-md-12">
                                        <input type="checkbox" id="remember">
                                        <label for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- <p class="mb-1">
                        @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900"
                            href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                        @endif
                    </p> -->
                </div>
            </div>
        </div>

        <script src="../../plugins/jquery/jquery.min.js"></script>

        <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

        <script src="../../dist/js/adminlte.min.js?v=3.2.0"></script>

    </body>
</html>