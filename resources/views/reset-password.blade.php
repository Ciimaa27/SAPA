```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SAPA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    <a href="{{ route('login') }}" class="back-btn">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7c5cc4" stroke-width="2.5">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
    </a>

    <div class="container">
        <div class="row h-100 align-items-center justify-content-center">

            <div class="col-md-5 pe-md-5">

                <div class="mb-4">
                    <h1 class="display-5 fw-bold text-orange">
                        Reset Password
                    </h1>

                    <p class="text-orange fs-6">
                        Masukkan password baru untuk akun SAPA Anda.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf

                    <input type="hidden"
                           name="user_id"
                           value="{{ $user->id }}">

                    <div class="mb-4">
                        <label class="custom-label">
                            Password Baru
                        </label>

                        <input type="password"
                               name="password"
                               class="form-control custom-input"
                               placeholder="Masukkan Password Baru"
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="custom-label">
                            Konfirmasi Password
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control custom-input"
                               placeholder="Konfirmasi Password Baru"
                               required>
                    </div>

                    <button type="submit"
                            class="btn btn-purple w-100 py-2 fw-bold">
                        Simpan Password
                    </button>

                </form>

            </div>

        </div>
    </div>

</body>
</html>
```
