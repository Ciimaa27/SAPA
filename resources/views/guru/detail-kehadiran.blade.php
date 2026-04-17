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

    <a href="{{ route('guru.kehadiran') }}" class="btn btn-kembali mb-3">
        ← Kembali
    </a>

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
                <input type="date" class="form-control" value="{{ $tanggal }}">
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
                        <div class="status-group">
                            <span class="status-btn active">H</span>
                            <span class="status-btn">I</span>
                            <span class="status-btn">S</span>
                            <span class="status-btn">A</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Tidak ada siswa dalam kelas ini</td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>

</div>
</div>

<script>
document.querySelectorAll('.status-group').forEach(group => {
    const buttons = group.querySelectorAll('.status-btn');

    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            buttons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>

@endsection
