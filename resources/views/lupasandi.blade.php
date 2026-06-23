<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - SAPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    <a href="{{ route('login') }}" class="back-btn">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7c5cc4" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
    </a>

    <div class="container">
        <div class="row h-100 align-items-center justify-content-center">

            <div class="col-md-5 pe-md-5">
                <div class="mb-4">
                    <h1 class="display-5 fw-bold text-orange">Lupa Kata sandi?</h1>
                    <p class="text-orange fs-6">Masukkan username dan email yang terdaftar agar Anda dapat melanjutkan reset kata sandi.</p>
                </div>

                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('password.verifikasi') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="custom-label">Username</label>
                        <input type="text"
                            class="form-control custom-input @error('username') is-invalid @enderror"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Masukkan Username"
                            required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="custom-label">Email</label>
                        <input type="email"
                            class="form-control custom-input @error('email') is-invalid @enderror"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan Email"
                            required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-purple w-100 py-2 fw-bold">Kirim</button>
                </form>
            </div>

        </div>
    </div>

</body>
</html>
