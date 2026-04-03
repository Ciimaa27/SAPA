@extends('layouts.app')

@section('title', 'Jadwal Pulang')

@section('content')

<link rel="stylesheet" href="{{ asset('css/jadwal_pulang.css') }}">
@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="container mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Jadwal Pulang</h4>
    <a href="{{ route('jadwal-pulang') }}" class="btn btn-warning btn-sm">
        ← Kembali
    </a>

    <!-- Card -->
    <div class="card shadow-sm p-4">

        @php
            $hari = [
                ['Senin', '10:30'],
                ['Selasa', '10:30'],
                ['Rabu', '10:30'],
                ['Kamis', '10:30'],
                ['Jumat', '09:30'],
                ['Sabtu', '10:30'],
            ];
        @endphp

        @foreach($hari as $item)

        @php
            $isLibur = $item[0] == 'Jumat'; // default Jumat libur
        @endphp

        <div class="row align-items-center mb-3 jadwal-row">

            <!-- Hari -->
            <div class="col-md-2">
                <div class="badge-hari">
                    {{ $item[0] }}
                </div>
            </div>

            <!-- Input Jam -->
            <div class="col-md-7">
                <input type="time"
                       class="form-control custom-input jam-input"
                       value="{{ $item[1] }}"
                       {{ $isLibur ? 'disabled' : '' }}>
            </div>

            <!-- Tombol Libur -->
            <div class="col-md-3">
                <button type="button"
                        class="btn btn-libur w-100 toggle-libur {{ $isLibur ? 'aktif' : '' }}">
                    Libur
                </button>
            </div>

        </div>
        @endforeach

        <!-- Button Simpan -->
        <div class="text-end mt-4">
            <button class="btn btn-success px-4">Simpan</button>
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
                // simpan nilai lama
                input.dataset.oldValue = input.value;

                input.disabled = true;
                input.value = '';
            } else {
                input.disabled = false;

                // kembalikan nilai lama
                input.value = input.dataset.oldValue || '';
            }

        });
    });

});
</script>

@endsection
