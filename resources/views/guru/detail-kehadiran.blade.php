@extends('layouts.app')

@section('title','Kehadiran Siswa')

@section('sidebar')
    @include('layouts.sidebar-guru')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/guru/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/siswa-kelas.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
<div class="container-dashboard">

    <div class="card mb-3 p-3">
        <h5 class="mb-0">Kehadiran Siswa</h5>
    </div>

<a href="{{ route('guru.kehadiran') }}" class="btn-kembali mb-3">
    <i class="fas fa-arrow-left"></i>
    Kembali
</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('guru.detail-kehadiran.save', $kelas->id_kelas) }}">
        @csrf

        <!-- INFO -->
        <div class="card mb-3 p-4">

            <div class="info-kelas">

                <div class="info-row">
                    <label>Kelas</label>
                    <span>:</span>
                    <input type="text" class="form-control" value="{{ $kelas->nama_kelas }}" readonly>
                </div>

                <div class="info-row">
                    <label>Wali kelas</label>
                    <span>:</span>
                    <input type="text" class="form-control" value="{{ $kelas->guru ? $kelas->guru->nama_guru : 'N/A' }}" readonly>
                </div>

                <div class="info-row">
                    <label>Tanggal</label>
                    <span>:</span>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
                </div>

            </div>

        </div>

        <!-- TABLE -->
        <div class="card p-3">
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

                @forelse($siswas as $i => $row)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $row->nis }}</td>
                    <td>{{ $row->nama_siswa }}</td>
                    <td>
                        <div class="status-group" data-siswa-id="{{ $row->id_siswa }}">
                            <span class="status-btn {{ $row->status_hadir === 'hadir' ? 'active' : '' }}">H</span>
                            <span class="status-btn {{ $row->status_hadir === 'izin' ? 'active' : '' }}">I</span>
                            <span class="status-btn {{ $row->status_hadir === 'sakit' ? 'active' : '' }}">S</span>
                            <span class="status-btn {{ $row->status_hadir === 'alpa' ? 'active' : '' }}">A</span>
                        </div>

                        <input
                            type="hidden"
                            name="status[{{ $row->id_siswa }}]"
                            id="status-{{ $row->id_siswa }}"
                            value="{{ $row->status_hadir ?? '' }}"
                        >
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Tidak ada siswa dalam kelas ini</td>
                </tr>
                @endforelse

            </tbody>

        </table>
        <div class="d-flex justify-content-end gap-2 mt-3">

    <a href="{{ route('guru.kehadiran') }}" class="btn btn-danger">
        Batal
    </a>

    <button type="submit" class="btn btn-success">
        Simpan
    </button>

</div>

        </div>
    </form>

</div>
</div>

<script>
document.querySelectorAll('.status-group').forEach(group => {
    const buttons = group.querySelectorAll('.status-btn');
    const statusInput = document.getElementById('status-' + group.dataset.siswaId);

    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            buttons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            if (statusInput) {
                const code = this.textContent.trim().toLowerCase();
                let value = 'hadir';

                if (code === 'i') {
                    value = 'izin';
                } else if (code === 's') {
                    value = 'sakit';
                } else if (code === 'a') {
                    value = 'alpa';
                }

                statusInput.value = value;
            }
        });
    });
});
</script>

@endsection
