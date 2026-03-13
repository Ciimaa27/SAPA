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
                    <p class="text-orange fs-6">Masukkan alamat email yang terdaftar. Tautan untuk mengatur ulang kata sandi akan dikirim ke email anda.</p>
                </div>

                <form action="/forgot-password" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="custom-label">Masukkan email</label>
                        <input type="email" class="form-control custom-input" name="email" placeholder="Contoh: user@gmail.com" required>
                    </div>

                    <button type="submit" class="btn btn-purple w-100 py-2 fw-bold">Kirim</button>
                </form>
            </div>

            <div class="col-md-4 text-center">
                <img src="{{ asset('foto/sapa.png') }}" alt="Logo" class="img-fluid logo-tangan">
            </div>

        </div>
    </div>

</body>
</html>
