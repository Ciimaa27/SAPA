@extends('layouts.app')

@section('title', 'Sidik Jari')

{{-- SIDEBAR --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- CSS --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/rfid.css') }}">
@endpush

{{-- CONTENT --}}
@section('content')
<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- TITLE -->
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Sidik Jari</h5>
        </div>

        <!-- TAB + SEARCH + REFRESH -->
            <div class="card mb-3 p-3">
                <div class="d-flex align-items-center gap-3">

                    <!-- TAB -->
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('iot.index', ['tab' => 'rfid']) }}"
                        class="btn btn-tab">
                            RFID
                        </a>

                        <a href="{{ route('iot.index', ['tab' => 'sidik-jari']) }}"
                        class="btn btn-tab active">
                            Sidik Jari
                        </a>
                    </div>

                    <!-- SEARCH + REFRESH -->
                    <div class="d-flex align-items-center gap-2 search-flex">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white">
                                <i class="fa fa-search"></i>
                            </span>

                            <input type="text"
                                id="searchInput"
                                class="form-control"
                                placeholder="Pencarian">
                        </div>

                        <button type="button"
                                class="btn btn-refresh btn-sm"
                                id="refreshTable"
                                title="Tampilkan semua data">
                            <i class="fa fa-refresh"></i>
                            Refresh
                        </button>
                    </div>

                </div>
            </div>

        <!-- FINGERPRINT REALTIME -->
        <div class="card p-4 mb-3 text-center">

            <h5 class="mb-3">
                Sidik Jari Terakhir Terdeteksi
            </h5>

            <h3 id="fingerprintID"
                class="mb-3"
                style="font-weight:bold; color:#6f42c1;">
                Menunggu scan...
            </h3>

            <div id="fingerprintStatus" class="mb-3">
                    <span class="status-fingerprint-belum">
                        Menunggu Sidik Jari
                    </span>
                </div>

                <div id="fingerprintStudent"
                    class="mb-2"
                    style="display: none;">
                    <span class="text-muted">
                        RFID Siswa:
                    </span>

                    <strong id="fingerprintStudentName">
                        -
                    </strong>
                </div>

                <p id="fingerprintInstruction"
                class="text-muted mb-0">
                    Lakukan enroll sidik jari pada alat.
                </p>
            </div>

        <!-- TABLE CONTAINER -->
        <div class="table-container">
            <table
                class="table table-hover align-middle mb-0"
                id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Wali</th>
                        <th>Hubungan</th>
                        <th>Siswa</th>
                        <th>ID Fingerprint</th>
                        <th>Status</th>
                        <th class="col-aksi">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        <tr data-siswa-id="{{ $item->id_siswa ?? '' }}">
                            <!-- NOMOR -->
                            <td>
                                {{
                                    ($data->currentPage() - 1)
                                    * $data->perPage()
                                    + $loop->iteration
                                }}
                            </td>
                            <!-- NAMA WALI -->
                            <td>
                                {{ $item->nama_wali }}
                            </td>
                            <!-- HUBUNGAN -->
                            <td>
                                {{ $item->hubungan ?? '-' }}
                            </td>
                            <!-- SISWA -->
                            <td>
                                {{ $item->nama_siswa ?? '-' }}
                            </td>
                            <!-- ID FINGERPRINT -->
                            <td>
                                @if($item->fingerprint_id)
                                    <span class="fingerprint-id">
                                        {{ $item->fingerprint_id }}
                                    </span>
                                @else
                                    <span class="text-muted">
                                        Belum ada
                                    </span>
                                @endif
                            </td>
                            <!-- STATUS -->
                            <td>
                                @if($item->fingerprint_id)
                                    <span class="status-fingerprint-terdaftar">
                                        Terdaftar
                                    </span>
                                @else
                                    <span class="status-fingerprint-belum">
                                        Belum Terdaftar
                                    </span>
                                @endif
                            </td>
                            <!-- AKSI -->
                            <td>
                                @if(!$item->fingerprint_id)
                                    <button
                                        type="button"
                                        class="btn-daftar-fingerprint"
                                        data-id="{{ $item->id_wali }}"
                                        data-nama="{{ $item->nama_wali }}"
                                        data-hubungan="{{ $item->hubungan ?? '-' }}"
                                        data-siswa="{{ $item->nama_siswa ?? '-' }}"
                                    >
                                        <i class="fa fa-plus"></i>
                                        Daftarkan
                                    </button>
                                @else
                                    <form
                                        action="{{ route(
                                            'iot.destroy',
                                            [
                                                'tab' => 'sidik-jari',
                                                'id' => $item->id_wali
                                            ]
                                        ) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn-lepas-fingerprint"
                                            onclick="return confirm(
                                                'Lepas sidik jari dari {{ $item->nama_wali }}?'
                                            )"
                                        >
                                            <i class="fa fa-unlink"></i>
                                            Lepas Sidik Jari
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="7"
                                class="text-center py-4 text-muted"
                            >
                                Tidak ada data wali.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="p-3 d-flex justify-content-end">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- MODAL DAFTARKAN SIDIK JARI --}}
<div class="modal fade"
     id="modalDaftarFingerprint"
     tabindex="-1"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST"
                  action="{{ route('iot.fingerprint.register') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        Daftarkan Sidik Jari
                    </h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    {{-- DATA YANG DIKIRIM --}}
                    <input type="hidden"
                           name="id_wali"
                           id="modalIdWali">
                    <input type="hidden"
                           name="fingerprint_id"
                           id="modalFingerprintId">
                    <div class="mb-3">
                        <small class="text-muted">
                            ID Sidik Jari
                        </small>
                        <div id="modalFingerprintText"
                             class="fw-semibold">
                            -
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">
                            Nama Wali
                        </small>
                        <div id="modalNamaWali"
                             class="fw-semibold">
                            -
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">
                            Hubungan
                        </small>
                        <div id="modalHubungan">
                            -
                        </div>
                    </div>
                    <div>
                        <small class="text-muted">
                            Siswa
                        </small>
                        <div id="modalSiswa">
                            -
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit"
                            class="btn-simpan-fingerprint">
                        Daftarkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SEARCH + REFRESH + FINGERPRINT REALTIME JS --}}
<script>
const searchInput = document.getElementById("searchInput");
const refreshButton = document.getElementById("refreshTable");
let lastFingerprint = null;
let fingerprintTerdaftar = false;

// ======================================================
// FILTER TABLE
// ======================================================
function filterTable(keyword) {
    keyword = String(keyword).toLowerCase().trim();
    const rows = document.querySelectorAll("#dataTable tbody tr");
    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase().trim();
        row.style.display = rowText.includes(keyword) ? "" : "none";
    });
}

// ======================================================
// FILTER WALI BERDASARKAN SISWA
// ======================================================
function filterBySiswa(idSiswa) {
    const rows = document.querySelectorAll("#dataTable tbody tr");

    rows.forEach(row => {
        const rowSiswaId = row.dataset.siswaId;

        row.style.display =
            String(rowSiswaId) === String(idSiswa)
                ? ""
                : "none";
    });
}

// ======================================================
// SEARCH MANUAL
// ======================================================
searchInput.addEventListener("keyup", function() {
    filterTable(this.value);
});

// ======================================================
// REFRESH
// ======================================================
refreshButton.addEventListener("click", function() {
    searchInput.value = "";
    const rows = document.querySelectorAll("#dataTable tbody tr");
    rows.forEach(row => { row.style.display = ""; });
});

// ======================================================
// LOAD FINGERPRINT
// ======================================================
function loadFingerprint() {

    fetch('/admin/latest-fingerprint')
        .then(res => {

            if (!res.ok) {
                throw new Error('Gagal mengambil fingerprint');
            }

            return res.json();
        })

        .then(data => {

            // Tidak ada hasil enroll
            if (
                !data ||
                data.fingerprint_id === null ||
                data.fingerprint_id === undefined
            ) {
                return;
            }

            const fingerprintID =
                String(data.fingerprint_id).trim();

            // Simpan data fingerprint terbaru
            lastFingerprint = fingerprintID;
            fingerprintTerdaftar = data.terdaftar;

            // Tampilkan ID fingerprint
            document.getElementById(
                'fingerprintID'
            ).innerText = 'ID ' + fingerprintID;


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


            // ==========================================
            // TAMPILKAN SISWA DARI RFID
            // ==========================================
            if (data.nama_siswa) {

                studentBox.style.display = "block";
                studentName.innerText = data.nama_siswa;

            } else {

                studentBox.style.display = "none";
                studentName.innerText = "-";
            }


            // ==========================================
            // FINGERPRINT SUDAH TERDAFTAR
            // ==========================================
            if (data.terdaftar) {

                status.innerHTML = `
                    <span class="status-fingerprint-terdaftar">
                        Terdaftar
                    </span>
                `;

                instruction.innerText =
                    'Sidik jari terdaftar atas nama ' +
                    data.nama_wali + '.';


                // Tampilkan semua data
                const rows =
                    document.querySelectorAll(
                        "#dataTable tbody tr"
                    );

                rows.forEach(row => {
                    row.style.display = "";
                });

            }

            // ==========================================
            // FINGERPRINT BELUM TERDAFTAR
            // ==========================================
            else {

                status.innerHTML = `
                    <span class="status-fingerprint-belum">
                        Belum Terdaftar
                    </span>
                `;

                instruction.innerText =
                    'Pilih wali siswa pada tabel, lalu klik Daftarkan.';


                // Filter hanya keluarga siswa
                if (data.id_siswa) {
                    filterBySiswa(data.id_siswa);
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
// ======================================================
// MODAL DAFTARKAN FINGERPRINT
// ======================================================

document.querySelectorAll('.btn-daftar-fingerprint')
    .forEach(button => {

        button.addEventListener('click', function () {

            // Belum ada hasil enroll
            if (!lastFingerprint) {

                alert(
                    'Belum ada sidik jari hasil enroll yang terdeteksi.'
                );

                return;
            }

            if (fingerprintTerdaftar) {

                alert(
                    'Sidik jari ini sudah terdaftar dan tidak dapat didaftarkan kembali.'
                );

                return;
            }


            // Isi ID wali
            document.getElementById(
                'modalIdWali'
            ).value = this.dataset.id;


            // Isi fingerprint ID
            document.getElementById(
                'modalFingerprintId'
            ).value = lastFingerprint;


            // Tampilkan fingerprint
            document.getElementById(
                'modalFingerprintText'
            ).innerText = 'ID ' + lastFingerprint;


            // Tampilkan nama wali
            document.getElementById(
                'modalNamaWali'
            ).innerText = this.dataset.nama;


            // Tampilkan hubungan
            document.getElementById(
                'modalHubungan'
            ).innerText = this.dataset.hubungan;


            // Tampilkan siswa
            document.getElementById(
                'modalSiswa'
            ).innerText = this.dataset.siswa;


            // Buka modal
            const modal = new bootstrap.Modal(
                document.getElementById(
                    'modalDaftarFingerprint'
                )
            );

            modal.show();

        });

    });

// ======================================================
// POLLING 2 DETIK
// ======================================================
setInterval(loadFingerprint, 2000);
loadFingerprint();
</script>
@endsection