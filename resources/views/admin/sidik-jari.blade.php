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

        <!-- 🔥 FINGERPRINT REALTIME -->
        <div class="card p-3 mb-3 text-center">
            <h5>Scan Sidik Jari Terakhir</h5>
            <h3 id="fingerprintID" style="font-weight:bold; color:#6f42c1;">Menunggu scan...</h3>
        </div>

        <!-- TABLE CONTAINER -->
        <div class="table-container">
            <table class="table table-hover align-middle mb-0" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Wali</th>
                        <th>ID Fingerprint</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                        <td>{{ $item->nama_wali }}</td>
                        <td>{{ $item->fingerprint_id ?? '-' }}</td>
                        <td>
                            <form action="{{ route('iot.destroy', ['tab' => 'sidik-jari', 'id' => $item->id_wali]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin hapus?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
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

{{-- SEARCH + REFRESH + FINGERPRINT REALTIME JS --}}
<script>
const searchInput = document.getElementById("searchInput");
const refreshButton = document.getElementById("refreshTable");
let lastFingerprint = null;

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

function filterFingerprintTable(fingerprintID) {
    const rows = document.querySelectorAll("#dataTable tbody tr");
    rows.forEach(row => {
        const fingerprintCell = row.cells[2];
        if (!fingerprintCell) return;

        const tableFingerprint = fingerprintCell.textContent.trim();
        row.style.display = tableFingerprint === String(fingerprintID) ? "" : "none";
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
        .then(res => res.json())
        .then(data => {
            if (data && data.fingerprint_id !== null && data.fingerprint_id !== undefined) {
                const fingerprintID = String(data.fingerprint_id).trim();
                document.getElementById('fingerprintID').innerText = fingerprintID;

                // hanya proses scan baru
                if (lastFingerprint !== fingerprintID) {
                    lastFingerprint = fingerprintID;
                    searchInput.value = fingerprintID;
                    filterFingerprintTable(fingerprintID);
                }
            }
        })
        .catch(error => {
            console.error('Fingerprint Error:', error);
        });
}

// ======================================================
// POLLING 2 DETIK
// ======================================================
setInterval(loadFingerprint, 2000);
loadFingerprint();
</script>
@endsection
