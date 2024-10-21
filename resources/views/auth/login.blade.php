<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - SB Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            body {
                background: linear-gradient(45deg, #1e3a8a, #f97316);
                min-height: 100vh;
            }
            .card {
                border-radius: 1rem;
            }
        </style>
    </head>
    <body>
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header">
                                        <h3 class="text-center font-weight-light my-4">Login</h3>
                                    </div>
                                    <div class="card-body">
                                        <!-- Session Status -->
                                        @if (session('status'))
                                            <div class="alert alert-success mb-4" role="alert">
                                                {{ session('status') }}
                                            </div>
                                        @endif

                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf

                                            <!-- Email Address -->
                                            <div class="form-floating mb-3">
                                                <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus placeholder="name@example.com" autocomplete="username" />
                                                <label for="email">Email address</label>
                                                @error('email')
                                                    <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Password -->
                                            <div class="form-floating mb-3">
                                                <input id="password" class="form-control" type="password" name="password" required placeholder="Password" autocomplete="current-password" />
                                                <label for="password">Password</label>
                                                @error('password')
                                                    <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Remember Me -->
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" id="remember_me" type="checkbox" name="remember" />
                                                <label class="form-check-label" for="remember_me">Remember me</label>
                                            </div>

                                            <!-- Actions -->
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                @if (Route::has('password.request'))
                                                    <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
                                                @endif
                                                <button type="submit" class="btn btn-primary">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small"><a href="{{ route('register') }}">Need an account? Sign up!</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
