@extends('layouts.app')

@section('title', 'Sidik Jari')

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
            <h5 class="mb-0">Sidik Jari</h5>
        </div>

        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('iot.index', ['tab' => 'rfid']) }}" class="btn btn-tab">RFID</a>
                    <a href="{{ route('iot.index', ['tab' => 'sidik-jari']) }}" class="btn btn-tab active">Sidik Jari</a>
                </div>
                <div class="d-flex align-items-center gap-2 search-flex">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fa fa-search"></i></span>
                       <input
                                type="text"
                                id="searchInput"
                                class="form-control"
                                placeholder="Pencarian"
                                value="{{ request('search') }}"
                            >
                    </div>
                    <button type="button" class="btn btn-refresh btn-sm" id="refreshTable" title="Tampilkan semua data">
                        <i class="fa fa-refresh"></i> Refresh
                    </button>
                </div>
            </div>
        </div>

        <div class="card p-4 mb-3 text-center">
            <h5 class="mb-3">Sidik Jari Terakhir Terdeteksi</h5>
            <h3 id="fingerprintID" class="mb-3" style="font-weight:bold; color:#6f42c1;">Menunggu scan...</h3>
            <div id="fingerprintStatus" class="mb-3">
                <span class="status-fingerprint-belum">Menunggu Sidik Jari</span>
            </div>
            <div id="fingerprintStudent" class="mb-2" style="display: none;">
                <span class="text-muted">RFID Siswa:</span>
                <strong id="fingerprintStudentName">-</strong>
            </div>
            <p id="fingerprintInstruction" class="text-muted mb-0">Lakukan enroll sidik jari pada alat.</p>
        </div>

        <div class="table-container">
            <table class="table table-hover align-middle mb-0" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Wali</th>
                        <th>Hubungan</th>
                        <th>Siswa</th>
                        <th>ID Fingerprint</th>
                        <th>Status</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        <tr data-siswa-id="{{ $item->id_siswa ?? '' }}">
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                            <td>{{ $item->nama_wali }}</td>
                            <td>{{ $item->hubungan ?? '-' }}</td>
                            <td>{{ $item->nama_siswa ?? '-' }}</td>
                            <td>
                                @if($item->fingerprint_id)
                                    <span class="fingerprint-id">{{ $item->fingerprint_id }}</span>
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </td>
                            <td>
                                @if($item->fingerprint_id)
                                    <span class="status-fingerprint-terdaftar">Terdaftar</span>
                                @else
                                    <span class="status-fingerprint-belum">Belum Terdaftar</span>
                                @endif
                            </td>
                            <td>
                                @if(!$item->fingerprint_id)
                                    <button type="button" class="btn-daftar-fingerprint" data-id="{{ $item->id_wali }}" data-nama="{{ $item->nama_wali }}" data-hubungan="{{ $item->hubungan ?? '-' }}" data-siswa="{{ $item->nama_siswa ?? '-' }}">
                                        <i class="fa fa-plus"></i> Daftarkan
                                    </button>
                                @else
                                    <button type="button" class="btn-lepas-fingerprint btn-buka-modal-lepas" data-id="{{ $item->id_wali }}" data-nama="{{ $item->nama_wali }}" data-fingerprint="{{ $item->fingerprint_id }}">
                                        <i class="fa fa-unlink"></i> Lepas Sidik Jari
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Tidak ada data wali.</td>
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

<!-- MODAL DAFTAR FINGERPRINT -->
<div class="modal fade" id="modalDaftarFingerprint" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('iot.fingerprint.register') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Daftarkan Sidik Jari</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_wali" id="modalIdWali">
                    <input type="hidden" name="fingerprint_id" id="modalFingerprintId">
                    <div class="mb-3">
                        <label class="form-label text-muted">ID Sidik Jari</label>
                        <div id="modalFingerprintText" class="modal-data-value uid-modal">-</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Nama Wali</label>
                        <div id="modalNamaWali" class="modal-data-value uid-modal">-</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Hubungan</label>
                        <div id="modalHubungan" class="modal-data-value uid-modal">-</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Siswa</label>
                        <div id="modalSiswa" class="modal-data-value uid-modal">-</div>
                    </div>
                    <div class="alert alert-light border mb-0">
                        <i class="fa fa-info-circle me-1"></i>Pastikan ID sidik jari dan wali yang dipilih sudah sesuai sebelum menyimpan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-simpan-rfid"><i class="fa fa-check me-1"></i>Daftarkan Sidik Jari</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL LEPAS FINGERPRINT -->
<div class="modal fade" id="modalLepasFingerprint" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formLepasFingerprint" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Lepas Sidik Jari</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Apakah Anda yakin ingin melepas sidik jari dari wali berikut?</p>
                    <div class="mb-3">
                        <label class="form-label text-muted">Nama Wali</label>
                        <div id="modalLepasNama" class="modal-data-value uid-modal">-</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">ID Sidik Jari</label>
                        <div id="modalLepasFingerprintId" class="modal-data-value uid-modal">-</div>
                    </div>
                    <div class="alert alert-warning mb-0">
                        <i class="fa fa-exclamation-triangle me-1"></i>Sidik jari akan dilepas dari data wali.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="fa fa-unlink me-1"></i>Ya, Lepaskan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let lastFingerprint = null;
    let fingerprintTerdaftar = false;

    // ID fingerprint yang sudah diproses oleh halaman ini
    let fingerprintHalaman = sessionStorage.getItem(
        'fingerprint_halaman'
    );


    // =====================================================
    // SEARCH
    // =====================================================
    const searchInput = document.getElementById('searchInput');

    let searchTimer = null;

    if (searchInput) {

        searchInput.addEventListener('input', function () {

            clearTimeout(searchTimer);

            searchTimer = setTimeout(() => {

                const keyword = searchInput.value.trim();

                const url = new URL(window.location.href);

                if (keyword !== '') {

                    url.searchParams.set(
                        'search',
                        keyword
                    );

                } else {

                    url.searchParams.delete('search');
                }

                url.searchParams.delete('page');

                window.location.href = url.toString();

            }, 500);
        });
    }


    // =====================================================
    // REFRESH
    // =====================================================
    const refreshButton =
        document.getElementById('refreshTable');

    if (refreshButton) {

        refreshButton.addEventListener('click', function () {

            const url = new URL(window.location.href);

            url.searchParams.delete('search');
            url.searchParams.delete('page');

            window.location.href = url.toString();
        });
    }


    // =====================================================
    // LOAD FINGERPRINT TERAKHIR
    // =====================================================
    function loadFingerprint() {

        fetch('/admin/latest-fingerprint', {
            cache: 'no-store'
        })

        .then(response => {

            if (!response.ok) {

                throw new Error(
                    'Gagal mengambil fingerprint'
                );
            }

            return response.json();
        })

        .then(data => {

            if (
                !data ||
                data.fingerprint_id === null ||
                data.fingerprint_id === undefined
            ) {
                return;
            }


            const fingerprintID =
                String(data.fingerprint_id).trim();


            lastFingerprint = fingerprintID;

            fingerprintTerdaftar =
                data.terdaftar;


            // =================================================
            // CEK FINGERPRINT BARU HASIL ENROLL
            // =================================================
            if (
                data.terdaftar === false &&
                data.id_siswa &&
                fingerprintHalaman !== fingerprintID
            ) {

                console.log(
                    'Fingerprint baru terdeteksi:',
                    fingerprintID
                );

                // Simpan supaya tidak reload terus-menerus
                sessionStorage.setItem(
                    'fingerprint_halaman',
                    fingerprintID
                );

                // Reload otomatis
                // Controller akan menampilkan wali siswa
                window.location.reload();

                return;
            }


            // =================================================
            // TAMPILKAN ID FINGERPRINT
            // =================================================
            const fingerprintIDElement =
                document.getElementById('fingerprintID');

            if (fingerprintIDElement) {

                fingerprintIDElement.innerText =
                    'ID ' + fingerprintID;
            }


            const status =
                document.getElementById(
                    'fingerprintStatus'
                );


            const instruction =
                document.getElementById(
                    'fingerprintInstruction'
                );


            const studentBox =
                document.getElementById(
                    'fingerprintStudent'
                );


            const studentName =
                document.getElementById(
                    'fingerprintStudentName'
                );


            // =================================================
            // TAMPILKAN SISWA
            // =================================================
            if (data.nama_siswa) {

                if (studentBox) {

                    studentBox.style.display = 'block';
                }

                if (studentName) {

                    studentName.innerText =
                        data.nama_siswa;
                }

            } else {

                if (studentBox) {

                    studentBox.style.display = 'none';
                }

                if (studentName) {

                    studentName.innerText = '-';
                }
            }


            // =================================================
            // STATUS FINGERPRINT
            // =================================================
            if (data.terdaftar) {

                if (status) {

                    status.innerHTML =
                        '<span class="status-fingerprint-terdaftar">Terdaftar</span>';
                }


                if (instruction) {

                    instruction.innerText =
                        'Sidik jari terdaftar atas nama ' +
                        data.nama_wali +
                        '.';
                }


                // Fingerprint sudah selesai didaftarkan.
                // Hapus penanda agar enroll berikutnya
                // dapat dideteksi sebagai proses baru.
                sessionStorage.removeItem(
                    'fingerprint_halaman'
                );

                fingerprintHalaman = null;

            } else {

                if (status) {

                    status.innerHTML =
                        '<span class="status-fingerprint-belum">Belum Terdaftar</span>';
                }


                if (instruction) {

                    instruction.innerText =
                        'Pilih wali siswa pada tabel, lalu klik Daftarkan.';
                }
            }
        })

        .catch(error => {

            console.error(
                'Fingerprint Error:',
                error
            );
        });
    }


    // =====================================================
    // TOMBOL DAFTARKAN FINGERPRINT
    // =====================================================
    document
        .querySelectorAll('.btn-daftar-fingerprint')
        .forEach(button => {

            button.addEventListener('click', function () {

                if (!lastFingerprint) {

                    alert(
                        'Belum ada sidik jari hasil enroll yang terdeteksi.'
                    );

                    return;
                }


                if (fingerprintTerdaftar) {

                    alert(
                        'Sidik jari ini sudah terdaftar.'
                    );

                    return;
                }


                document.getElementById(
                    'modalIdWali'
                ).value = this.dataset.id;


                document.getElementById(
                    'modalFingerprintId'
                ).value = lastFingerprint;


                document.getElementById(
                    'modalFingerprintText'
                ).innerText =
                    'ID ' + lastFingerprint;


                document.getElementById(
                    'modalNamaWali'
                ).innerText =
                    this.dataset.nama;


                document.getElementById(
                    'modalHubungan'
                ).innerText =
                    this.dataset.hubungan;


                document.getElementById(
                    'modalSiswa'
                ).innerText =
                    this.dataset.siswa;


                const modal =
                    new bootstrap.Modal(
                        document.getElementById(
                            'modalDaftarFingerprint'
                        )
                    );


                modal.show();
            });
        });


    // =====================================================
    // TOMBOL LEPAS FINGERPRINT
    // =====================================================
    document
        .querySelectorAll('.btn-buka-modal-lepas')
        .forEach(button => {

            button.addEventListener('click', function () {

                document.getElementById(
                    'modalLepasNama'
                ).innerText =
                    this.dataset.nama;


                document.getElementById(
                    'modalLepasFingerprintId'
                ).innerText =
                    'ID ' +
                    this.dataset.fingerprint;


                document.getElementById(
                    'formLepasFingerprint'
                ).action =
                    "{{ url('/admin/iot/sidik-jari') }}/" +
                    this.dataset.id;


                const modal =
                    new bootstrap.Modal(
                        document.getElementById(
                            'modalLepasFingerprint'
                        )
                    );


                modal.show();
            });
        });


    // =====================================================
    // LOAD AWAL
    // =====================================================
    loadFingerprint();


    // =====================================================
    // CEK SETIAP 2 DETIK
    // =====================================================
    setInterval(function () {

        loadFingerprint();

    }, 2000);

});
</script>
@endsection

