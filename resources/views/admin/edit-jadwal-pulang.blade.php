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

        <a href="{{ route('jadwal-pulang') }}" class="btn-kembali">
            ← Kembali
        </a>
    </div>

    <!-- CARD -->
    <div class="card-jadwal">

        @php
            $hari = [
                ['Senin', '10:30'],
                ['Selasa', '10:30'],
                ['Rabu', '10:30'],
                ['Kamis', '10:30'],
                ['Jumat', '09:30'], // sekarang tidak otomatis libur
                ['Sabtu', '10:30'],
            ];
        @endphp

        @foreach($hari as $item)

        @php
            $isLibur = false; // ✅ semua hari default tidak libur
        @endphp

        <div class="jadwal-row">

            <!-- HARI -->
            <div class="hari-box">
                {{ $item[0] }}
            </div>

            <!-- JAM -->
            <input type="time"
                   class="jam-input"
                   value="{{ $item[1] }}"
                   min="07:00"
                   max="17:00"
                   step="1800">

            <!-- LIBUR -->
            <button type="button"
                    class="btn-libur toggle-libur">
                Libur
            </button>

        </div>

        @endforeach

        <!-- SIMPAN -->
        <div class="text-end mt-4">
            <button class="btn-simpan">Simpan</button>
        </div>

    </div>

</div>

<!-- SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const buttons = document.querySelectorAll('.toggle-libur');

    buttons.forEach((btn) => {
        btn.addEventListener('click', function () {

            const row = btn.closest('.jadwal-row');
            const input = row.querySelector('.jam-input');

            btn.classList.toggle('aktif');

            if (btn.classList.contains('aktif')) {
                // 🔒 kunci input
                input.disabled = true;

                // kasih style libur
                input.classList.add('libur-mode');

                // ubah warna tombol (opsional biar merah)
                btn.style.backgroundColor = '#f8d7da';
                btn.style.color = '#842029';

            } else {
                // buka kembali input
                input.disabled = false;
                input.classList.remove('libur-mode');

                // balikin style tombol
                btn.style.backgroundColor = '';
                btn.style.color = '';
            }

        });
    });

});
</script>

@endsection
