@extends('layouts.app')

@section('title', 'RFID & Sidik Jari')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/rfid.css') }}">
@endpush

@section('content')
<div class="main-dashboard">
    <div class="container-dashboard">

        <div class="card mb-3 p-3">
            <h5 class="mb-0">RFID</h5>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('iot.index', ['tab' => 'rfid']) }}"
                class="btn btn-tab {{ ($tab ?? 'rfid') == 'rfid' ? 'active' : '' }}">
                    RFID
                </a>
                <a href="{{ route('iot.index', ['tab' => 'sidik-jari']) }}"
                class="btn btn-tab {{ ($tab ?? '') == 'sidik-jari' ? 'active' : '' }}">
                    Sidik jari
                </a>
                <form method="GET"
                    action="{{ route('iot.index', ['tab' => $tab]) }}"
                    class="d-flex align-items-center gap-2">

                    <div class="input-group input-group-sm" style="width:260px;">
                        <span class="input-group-text bg-white">
                            <i class="fa fa-search"></i>
                        </span>

                        <input type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Pencarian...">
                    </div>

                    <button class="btn btn-refresh btn-sm">
                        Cari
                    </button>

                    <a href="{{ route('iot.index',['tab'=>$tab]) }}"
                    class="btn btn-refresh btn-sm">
                        Refresh
                    </a>

                </form>

            </div>
        </div>

        @if($tab == 'rfid')
        <div class="card p-4 mb-3 text-center scan-card">
            <h5 class="scan-title">RFID Terakhir Terdeteksi</h5>
            <h3 id="rfidUID" class="scan-value">Menunggu scan...</h3>
            <div id="rfidStatus" class="mt-2">
                <span class="badge bg-secondary">Menunggu kartu</span>
            </div>
            <p id="rfidInstruction" class="text-muted mt-3 mb-0">Tempelkan kartu RFID pada perangkat pembaca.</p>
        </div>
        @endif

        <div class="card">
            <div class="table-container">
                <table class="table table-hover align-middle mb-0" id="dataTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            @if($tab == 'rfid')
                                <th>Nama Siswa</th>
                                <th>UID RFID</th>
                                <th>Status</th>
                            @else
                                <th>Nama Wali</th>
                                <th>ID Fingerprint</th>
                            @endif
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $item)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                            @if($tab == 'rfid')
                                <td>{{ $item->nama_siswa }}</td>
                                <td>
                                    @if($item->rfid_uid)
                                        <span class="uid-rfid">{{ $item->rfid_uid }}</span>
                                    @else
                                        <span class="text-muted">Belum ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->rfid_uid)
                                        <span class="badge status-terdaftar">Terdaftar</span>
                                    @else
                                        <span class="badge status-belum">Belum Terdaftar</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$item->rfid_uid)
                                        <button type="button" class="btn btn-daftar-rfid btn-sm" data-id="{{ $item->id_siswa }}" data-nama="{{ $item->nama_siswa }}">
                                            <i class="fa fa-plus me-1"></i>Daftarkan
                                        </button>
                                    @else
                                        <button type="button"
                                                class="btn btn-lepas-rfid btn-sm btn-buka-modal-lepas-rfid"

                                                data-url="{{ route('iot.destroy', [
                                                    'tab' => 'rfid',
                                                    'id' => $item->id_siswa
                                                ]) }}"

                                                data-nama="{{ $item->nama_siswa }}"
                                                data-uid="{{ $item->rfid_uid }}">

                                            <i class="fa fa-unlink me-1"></i>
                                            Lepas RFID

                                        </button>
                                    @endif
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $tab == 'rfid' ? 5 : 4 }}" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 d-flex justify-content-end">
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        @if ($data->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">‹</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $data->previousPageUrl() }}">‹</a></li>
                        @endif

                        @php
                            $current = $data->currentPage();
                            $last = $data->lastPage();
                        @endphp

                        @if ($current > 3)
                            <li class="page-item"><a class="page-link" href="{{ $data->url(1) }}">1</a></li>
                            @if ($current > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif

                        @for ($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++)
                            <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if ($current < $last - 2)
                            @if ($current < $last - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item"><a class="page-link" href="{{ $data->url($last) }}">{{ $last }}</a></li>
                        @endif

                        @if ($data->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $data->nextPageUrl() }}">›</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">›</span></li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="registerRfidModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('iot.rfid.register') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Daftarkan RFID</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_siswa" id="modalSiswaId">
                    <input type="hidden" name="uid_rfid" id="modalRfidUid">
                    <div class="mb-3">
                        <label class="form-label text-muted">Nama Siswa</label>
                        <div class="modal-data-value uid-modal" id="modalNamaSiswa">-</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">UID RFID</label>
                        <div class="modal-data-value uid-modal" id="modalUidDisplay">-</div>
                    </div>
                    <div class="alert alert-light border mb-0">
                        <i class="fa fa-info-circle me-1"></i>Pastikan kartu RFID dan siswa yang dipilih sudah sesuai sebelum menyimpan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-simpan-rfid">
                        <i class="fa fa-check me-1"></i>Daftarkan RFID
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade"
     id="modalLepasRfid"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form id="formLepasRfid"
                  method="POST">

                @csrf
                @method('DELETE')

                <div class="modal-header">

                    <h5 class="modal-title">
                        Lepas RFID
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <p class="mb-3">
                        Apakah Anda yakin ingin melepaskan RFID dari siswa berikut?
                    </p>


                    <div class="mb-3">

                        <label class="form-label text-muted">
                            Nama Siswa
                        </label>

                        <div class="modal-data-value uid-modal"
                             id="modalLepasNamaSiswa">
                            -
                        </div>

                    </div>


                    <div class="mb-3">

                        <label class="form-label text-muted">
                            UID RFID
                        </label>

                        <div class="modal-data-value uid-modal"
                             id="modalLepasUid">
                            -
                        </div>

                    </div>


                    <div class="alert alert-warning mb-0">

                        <i class="fa fa-exclamation-triangle me-1"></i>

                        RFID akan dilepas dari data siswa dan dapat didaftarkan kembali ke siswa lain.

                    </div>

                </div>
                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                        Batal

                    </button>


                    <button type="submit"
                            class="btn btn-danger">

                        <i class="fa fa-unlink me-1"></i>
                        Ya, Lepaskan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
    let lastRFID = null;

    function loadRFID() {
        fetch('/admin/latest-rfid')
            .then(res => res.json())
            .then(data => {
                if (!data || !data.uid_rfid) return;

                document.getElementById('rfidUID').innerText = data.uid_rfid;
                lastRFID = data.uid_rfid;

                const status = document.getElementById('rfidStatus');
                const instruction = document.getElementById('rfidInstruction');

                if (data.terdaftar) {
                    status.innerHTML = `<span class="badge status-terdaftar">Terdaftar</span>`;
                    instruction.innerText = 'Kartu RFID terdaftar atas nama ' + data.nama_siswa + '.';
                } else {
                    status.innerHTML = `<span class="badge bg-warning text-dark">Belum Terdaftar</span>`;
                    instruction.innerText = 'Pilih siswa pada tabel di bawah untuk mendaftarkan kartu RFID ini.';
                }
            })
            .catch(error => {
                console.error('RFID Error:', error);
            });
    }

    @if($tab == 'rfid')
        setInterval(loadRFID, 2000);
        loadRFID();
    @endif
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.btn-daftar-rfid');
    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            if (!lastRFID) {
                alert('Belum ada kartu RFID yang terdeteksi. Silakan tempelkan kartu terlebih dahulu.');
                return;
            }

            const siswaId = this.dataset.id;
            const namaSiswa = this.dataset.nama;

            document.getElementById('modalSiswaId').value = siswaId;
            document.getElementById('modalNamaSiswa').innerText = namaSiswa;
            document.getElementById('modalRfidUid').value = lastRFID;
            document.getElementById('modalUidDisplay').innerText = lastRFID;

            const modalElement = document.getElementById('registerRfidModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
            modal.show();
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const tombolLepas =
        document.querySelectorAll(
            '.btn-buka-modal-lepas-rfid'
        );

    tombolLepas.forEach(function (button) {

        button.addEventListener(
            'click',
            function () {

                const namaSiswa =
                    this.dataset.nama;

                const uidRfid =
                    this.dataset.uid;

                const url =
                    this.dataset.url;


                document.getElementById(
                    'modalLepasNamaSiswa'
                ).innerText = namaSiswa;


                document.getElementById(
                    'modalLepasUid'
                ).innerText = uidRfid;


                document.getElementById(
                    'formLepasRfid'
                ).action = url;


                const modalElement =
                    document.getElementById(
                        'modalLepasRfid'
                    );


                const modal =
                    bootstrap.Modal.getOrCreateInstance(
                        modalElement
                    );


                modal.show();

            }
        );

    });

});
</script>
@endsection
