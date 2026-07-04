@extends('layouts.app')

@section('title','Data siswa kelas')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/siswa-kelas.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Data siswa kelas</h5>
        </div>

        <!-- TOMBOL KEMBALI -->
        <a href="{{ route('kelas') }}" class="btn btn-kembali mb-3">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>


        <!-- INFORMASI KELAS -->
        <div class="card mb-3 p-4">

            <div class="info-kelas">

                <div class="info-row">
                    <label>Kelas</label>
                    <span>:</span>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $kelas->nama_kelas ?? '-' }}"
                        readonly
                    >
                </div>


                <div class="info-row">
                    <label>Wali kelas</label>
                    <span>:</span>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $kelas->nama_guru ?? '-' }}"
                        readonly
                    >
                </div>


                <div class="info-row">
                    <label>Tanggal</label>
                    <span>:</span>

                    <form
                        method="GET"
                        action="{{ route('siswa-kelas', $kelas->id_kelas) }}"
                        style="margin: 0;"
                    >
                        <input
                            type="date"
                            name="tanggal"
                            class="form-control"
                            value="{{ $tanggal }}"
                            onchange="this.form.submit()"
                        >
                    </form>
                </div>


                <div class="info-row">
                    <label>Jumlah siswa</label>
                    <span>:</span>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $siswa->count() }}"
                        readonly
                    >
                </div>

            </div>

        </div>


        <!-- TABEL KEHADIRAN -->
        <div class="card">

            <form
                method="POST"
                action="{{ route('update-kehadiran-kelas', $kelas->id_kelas) }}"
            >
                @csrf

                <input
                    type="hidden"
                    name="tanggal"
                    value="{{ $tanggal }}"
                >


                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama lengkap</th>
                                <th>Status</th>
                            </tr>
                        </thead>


                        <tbody>

                            @forelse($siswa as $row)

                                @php
                                    $statusValue = strtolower(
                                        $row->status_hadir ?? ''
                                    );
                                @endphp

                                <tr>

                                    <!-- NO -->
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <!-- NIS -->
                                    <td>
                                        {{ $row->nis ?? '-' }}
                                    </td>


                                    <!-- NAMA SISWA -->
                                    <td>
                                        {{ $row->nama_siswa ?? '-' }}
                                    </td>


                                    <!-- STATUS KEHADIRAN -->
                                    <td>

                                        <div
                                            class="status-group"
                                            data-siswa-id="{{ $row->id_siswa }}"
                                        >

                                            <!-- HADIR -->
                                            <button
                                                type="button"
                                                class="status-btn btn btn-sm
                                                {{ $statusValue === 'hadir' ? 'active' : '' }}"
                                                data-status="hadir"
                                                title="Hadir"
                                            >
                                                H
                                            </button>


                                            <!-- IZIN -->
                                            <button
                                                type="button"
                                                class="status-btn btn btn-sm
                                                {{ $statusValue === 'izin' ? 'active' : '' }}"
                                                data-status="izin"
                                                title="Izin"
                                            >
                                                I
                                            </button>


                                            <!-- SAKIT -->
                                            <button
                                                type="button"
                                                class="status-btn btn btn-sm
                                                {{ $statusValue === 'sakit' ? 'active' : '' }}"
                                                data-status="sakit"
                                                title="Sakit"
                                            >
                                                S
                                            </button>


                                            <!-- ALPA -->
                                            <button
                                                type="button"
                                                class="status-btn btn btn-sm
                                                {{ $statusValue === 'alpa' ? 'active' : '' }}"
                                                data-status="alpa"
                                                title="Alpa"
                                            >
                                                A
                                            </button>


                                            <!-- NILAI STATUS -->
                                            <input
                                                type="hidden"
                                                name="status[{{ $row->id_siswa }}]"
                                                class="status-input"
                                                value="{{ $statusValue }}"
                                            >

                                        </div>

                                    </td>

                                </tr>


                            @empty

                                <tr>
                                    <td
                                        colspan="4"
                                        class="text-center"
                                    >
                                        Tidak ada siswa di kelas ini
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                <!-- TOMBOL AKSI -->
                <div class="card-footer-absen">
                    <div class="form-action">
                        <a href="{{ route('kelas') }}" class="btn-batal">
                            Batal
                        </a>

                        <button type="submit" class="btn-simpan">
                            Simpan
                        </button>
                    </div>
                </div>

                            </form>

                        </div>

                    </div>
                </div>


<!-- SCRIPT STATUS KEHADIRAN -->
<script>
document.querySelectorAll('.status-group').forEach(group => {

    const buttons = group.querySelectorAll('.status-btn');
    const statusInput = group.querySelector('.status-input');

    const activeButton = Array.from(buttons).find(
        btn => btn.classList.contains('active')
    );

    if (activeButton && statusInput && !statusInput.value) {
        statusInput.value = activeButton.dataset.status;
    }


    buttons.forEach(btn => {

        btn.addEventListener('click', () => {

            buttons.forEach(button => {
                button.classList.remove('active');
            });

            btn.classList.add('active');

            if (statusInput) {
                statusInput.value = btn.dataset.status;
            }

        });

    });

});
</script>


<!-- ALERT SUCCESS -->
@if(session('success'))

<script>
    const message = @json(session('success'));
    alert(message);
</script>

@endif

@endsection