<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password SAPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

```
<div class="card shadow">
    <div class="card-body">

        <h3 class="mb-4">
            Reset Password
        </h3>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <input type="hidden"
                   name="user_id"
                   value="{{ $user->id }}">

            <div class="mb-3">
                <label>Password Baru</label>
                <input type="password"
                       name="password"
                       class="form-control"
                       required>
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password"
                       name="password_confirmation"
                       class="form-control"
                       required>
            </div>

            <button type="submit"
                    class="btn btn-primary">
                Simpan Password
            </button>

        </form>

    </div>
</div>
```

</div>

</body>
</html>
