<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SAPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    <div class="container">
        <div class="row h-100 align-items-center justify-content-center">

            <div class="col-md-5 pe-md-5">
                <div class="mb-4">
                    <h1 class="display-6 fw-bold text-orange">Masuk ke Sistem SAPA</h1>
                    <p class="text-orange fs-6">Silahkan masuk menggunakan akun yang diberikan oleh Administrator</p>
                </div>

                <form action="/login" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="custom-label">Nama Pengguna</label>
                        <input type="text" class="form-control custom-input" name="username" placeholder="Masukkan Username" required>
                    </div>

                    <div class="mb-2">
                        <label class="custom-label">Kata sandi</label>
                        <input type="password" class="form-control custom-input" name="password" placeholder="******" required>
                    </div>

                    <div class="text-end mb-4">
                        <a href="{{ route('lupasandi') }}" class="text-danger x-small text-decoration-none">Lupa kata sandi?</a>
                    </div>

                    <button type="submit" class="btn btn-purple w-100 py-2 fw-bold">Masuk</button>
                </form>
            </div>

            <div class="col-md-4 text-center">
                <img src="{{ asset('foto/sapa.png') }}" alt="Logo" class="img-fluid logo-tangan">
            </div>

        </div>
    </div>

</body>
</html>
