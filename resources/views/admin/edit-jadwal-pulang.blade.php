@extends('layouts.app')

@section('title', 'Jadwal Pulang')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/edit-jadwal-pulang.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="container mt-4">

    <!-- HEADER -->
    <div class="mb-3">
        <h2 class="judul-halaman">Jadwal Pulang</h2>

        <a href="{{ route('jadwal-pulang', ['kelas' => $activeKelas ?? 1]) }}" class="btn-kembali">
            ← Kembali
        </a>
    </div>

    <div class="class-card">
        <div class="class-tabs">
            @for($i = 1; $i <= 6; $i++)
                <a href="{{ route('jadwal-pulang.edit', ['kelas' => $i]) }}"
                   class="btn-kelas {{ ($activeKelas ?? 1) === $i ? 'active' : '' }}">
                    Kelas {{ $i }}
                </a>
            @endfor
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form id="jadwalForm" action="{{ route('jadwal-pulang.update', ['kelas' => $activeKelas ?? 1]) }}" method="POST" data-kelas="{{ $activeKelas ?? 1 }}">
        @csrf

        <div class="card-jadwal">

            @foreach($jadwal as $hari => $item)
                <div class="jadwal-row">

                    <!-- HARI -->
                    <div class="hari-box">
                        {{ $hari }}
                    </div>

                    <!-- JAM -->
                    <input type="time"
                           class="jam-input {{ $item['libur'] ? 'libur-mode' : '' }}"
                           name="jadwal[{{ $hari }}][jam]"
                           value="{{ $item['jam'] }}"
                           min="07:00"
                           max="17:00"
                           step="1800"
                           {{ $item['libur'] ? 'readonly' : '' }}>

                    <!-- LIBUR -->
                    <button type="button"
                            class="btn-libur toggle-libur {{ $item['libur'] ? 'aktif' : '' }}"
                            data-hari="{{ $hari }}">
                        Libur
                    </button>
                    <input type="hidden" name="jadwal[{{ $hari }}][libur]" value="{{ $item['libur'] ? '1' : '0' }}">

                </div>
            @endforeach

            <!-- SIMPAN -->
            <div class="text-end mt-4">
                <button class="btn-simpan" type="submit">Simpan</button>
            </div>

        </div>
    </form>

</div>

<!-- SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const buttons = document.querySelectorAll('.toggle-libur');

    buttons.forEach((btn) => {
        btn.addEventListener('click', function () {

            const row = btn.closest('.jadwal-row');
            const input = row.querySelector('.jam-input');
            const hiddenLibur = row.querySelector('input[type="hidden"]');

            btn.classList.toggle('aktif');

            if (btn.classList.contains('aktif')) {
                // 🔒 kunci input dan kosongkan nilai
                input.readOnly = true;
                input.value = '';

                // kasih style libur
                input.classList.add('libur-mode');

                // ubah warna tombol (opsional biar merah)
                btn.style.backgroundColor = '#f8d7da';
                btn.style.color = '#842029';
                hiddenLibur.value = '1';
            } else {
                // buka kembali input
                input.readOnly = false;
                input.classList.remove('libur-mode');

                // balikin style tombol
                btn.style.backgroundColor = '';
                btn.style.color = '';
                hiddenLibur.value = '0';
            }

        });
    });

    // AJAX submit form
    const form = document.getElementById('jadwalForm');
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json().then(data => ({ status: response.status, data })))
        .then(({ status, data }) => {
            if (status === 200 && data.success) {
                showSuccessMessage(data.message || 'Jadwal berhasil disimpan.');
                // Redirect ke halaman jadwal pulang setelah 1 detik
                setTimeout(() => {
                    const kelas = form.dataset.kelas;
                    window.location.href = `/admin/jadwal-pulang?kelas=${kelas}`;
                }, 1000);
            } else if (status === 422 && data.errors) {
                const firstError = Object.values(data.errors)[0][0];
                showErrorMessage(firstError || 'Validasi gagal.');
            } else {
                showErrorMessage(data.message || 'Terjadi kesalahan saat menyimpan.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('Terjadi kesalahan jaringan.');
        });
    });

    function showSuccessMessage(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success';
        alertDiv.textContent = message;
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '9999';
        document.body.appendChild(alertDiv);
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }

    function showErrorMessage(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger';
        alertDiv.textContent = message;
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '9999';
        document.body.appendChild(alertDiv);
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }

});
</script>

@endsection
