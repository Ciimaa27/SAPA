@extends('layouts.app')

@section('title','Data siswa kelas')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/siswa-kelas.css') }}">

    @include('layouts.sidebar-admin')
    @include('layouts.topbar')

    <div class="main-dashboard">
    <div class="container-dashboard">

        <div class="card mb-3 p-3">
            <h5 class="mb-0">Data siswa kelas</h5>
        </div>

            <a href="{{ route('kelas') }}" class="btn btn-kembali mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <div class="card mb-3 p-4">

            <div class="info-kelas">

                <div class="info-row">
                    <label>Kelas</label>
                    <span>:</span>
                    <input type="text" class="form-control" value="{{ $kelas->nama_kelas ?? '-' }}" readonly>
                </div>

                <div class="info-row">
                    <label>Wali kelas</label>
                    <span>:</span>
                    <input type="text" class="form-control" value="{{ $kelas->nama_guru ?? '-' }}" readonly>
                </div>

                <div class="info-row">
                    <label>Tanggal</label>
                    <span>:</span>
                    <form method="GET" action="{{ route('siswa-kelas', $kelas->id_kelas) }}" style="margin: 0;">
                        <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" onchange="this.form.submit()">
                    </form>
                </div>
                <div class="info-row">
                    <label>Tgl DB</label>
                    <span>:</span>
                    <select class="form-control" onchange="window.location.href=this.value">
                        <option value="">Pilih tanggal dari DB</option>
                        @foreach($availableDates as $availableDate)
                            <option value="{{ route('siswa-kelas', $kelas->id_kelas) . '?tanggal=' . $availableDate }}" {{ $availableDate === $tanggal ? 'selected' : '' }}>{{ \Carbon\Carbon::parse($availableDate)->format('d/m/Y') }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="info-row">
                    <label>Jumlah siswa</label>
                    <span>:</span>
                    <input type="text" class="form-control" value="{{ $siswa->count() }}" readonly>
                </div>

            </div>

        </div>

        <div class="card">
            <form method="POST" action="{{ route('update-kehadiran-kelas', $kelas->id_kelas) }}">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama lengkap</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($siswa as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->nis ?? '-' }}</td>
                                <td>{{ $row->nama_siswa ?? '-' }}</td>
                                <td>
                                    <div class="status-group" data-siswa-id="{{ $row->id_siswa }}">
                                        @php $statusValue = strtolower($row->status_hadir ?? ''); @endphp
                                        <button type="button" class="status-btn btn btn-sm {{ $statusValue === 'hadir' ? 'active' : '' }}" data-status="hadir" title="Hadir">H</button>
                                        <button type="button" class="status-btn btn btn-sm {{ $statusValue === 'izin' ? 'active' : '' }}" data-status="izin" title="Izin">I</button>
                                        <button type="button" class="status-btn btn btn-sm {{ $statusValue === 'sakit' ? 'active' : '' }}" data-status="sakit" title="Sakit">S</button>
                                        <button type="button" class="status-btn btn btn-sm {{ $statusValue === 'alpa' ? 'active' : '' }}" data-status="alpa" title="Alpa">A</button>
                                        <input type="hidden" name="status[{{ $row->id_siswa }}]" class="status-input" value="{{ $statusValue }}">
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="keterangan[{{ $row->id_siswa }}]" class="form-control form-control-sm" value="{{ $row->keterangan ?? '' }}" placeholder="Keterangan">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada siswa di kelas ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('kelas') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    document.querySelectorAll('.status-group').forEach(group => {
        const buttons = group.querySelectorAll('.status-btn');
        const statusInput = group.querySelector('.status-input');

        // Set initial value from currently active button
        const activeButton = Array.from(buttons).find(btn => btn.classList.contains('active'));
        if (activeButton && statusInput && !statusInput.value) {
            statusInput.value = activeButton.dataset.status;
        }

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                buttons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                if (statusInput) {
                    statusInput.value = btn.dataset.status;
                }
            });
        });
    });
</script>

@if(session('success'))
<script>
    var message = @json(session('success'));
    alert(message);
</script>
@endif

@endsection
