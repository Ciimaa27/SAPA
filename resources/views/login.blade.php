<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SAPA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICON -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <!-- BACK BUTTON -->
    <a href="/" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>

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

                    <!-- PASSWORD -->
                    <div class="mb-2">
                        <label class="custom-label">Kata sandi</label>

                        <div class="position-relative">
                            <input type="password" id="password" class="form-control custom-input pe-5" name="password" placeholder="******" required>

                            <i class="bi bi-eye toggle-password" onclick="togglePassword()"></i>
                        </div>
                    </div>

                    <div class="text-end mb-4">
                        <a href="{{ route('lupasandi') }}" class="text-danger x-small text-decoration-none">Lupa kata sandi?</a>
                    </div>

                    <button type="submit" class="btn btn-purple w-100 py-2 fw-bold">Masuk</button>
                </form>
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const icon = document.querySelector(".toggle-password");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }
    </script>

</body>
</html>
