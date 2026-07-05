@extends('layouts.app')

@section('title', 'RFID')

{{-- SIDEBAR --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/rfid.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

{{-- CONTENT --}}
@section('content')
<div class="main-dashboard">
    <div class="container-dashboard">

        {{-- TITLE --}}
        <div class="card mb-3 p-3">
            <h5 class="mb-0">RFID</h5>
        </div>

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- SEARCH + REFRESH --}}
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-2">
                {{-- SEARCH --}}
                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Pencarian">
                </div>

                {{-- REFRESH --}}
                <button type="button" class="btn btn-refresh btn-sm" id="refreshTable" title="Tampilkan semua data">
                    <i class="fa fa-refresh"></i> Refresh
                </button>
            </div>
        </div>

        {{-- RFID REALTIME --}}
        <div class="card p-3 mb-3 text-center">
            <h5>Scan RFID Terakhir</h5>
            <h3 id="rfidUID" style="font-weight:bold; color:#6f42c1;">Menunggu scan...</h3>
        </div>

        {{-- TABLE CARD --}}
        <div class="card">
            {{-- TABLE --}}
            <div class="table-container">
                <table class="table table-hover align-middle mb-0" id="dataTable">
                    {{-- TABLE HEADER --}}
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>UID</th>
                        </tr>
                    </thead>

                    {{-- TABLE BODY --}}
                    <tbody>
                        @forelse($data as $item)
                            <tr>
                                {{-- NOMOR --}}
                                <td>
                                    {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                </td>

                                {{-- NAMA SISWA --}}
                                <td>{{ $item->nama_siswa }}</td>

                                {{-- RFID UID --}}
                                <td>{{ $item->rfid_uid ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="p-3 d-flex justify-content-end">
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        {{-- PREVIOUS --}}
                        @if($data->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">‹</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $data->previousPageUrl() }}">‹</a></li>
                        @endif

                        {{-- PAGE VARIABLES --}}
                        @php
                            $current = $data->currentPage();
                            $last = $data->lastPage();
                        @endphp

                        {{-- FIRST PAGE --}}
                        @if($current > 3)
                            <li class="page-item"><a class="page-link" href="{{ $data->url(1) }}">1</a></li>
                            @if($current > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif

                        {{-- MIDDLE PAGE --}}
                        @for($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++)
                            <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- LAST PAGE --}}
                        @if($current < $last - 2)
                            @if($current < $last - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item"><a class="page-link" href="{{ $data->url($last) }}">{{ $last }}</a></li>
                        @endif

                        {{-- NEXT --}}
                        @if($data->hasMorePages())
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

{{-- SEARCH + REFRESH SCRIPT --}}
<script>
const searchInput = document.getElementById("searchInput");
const refreshButton = document.getElementById("refreshTable");

function filterTable(keyword) {
    keyword = String(keyword).toLowerCase().trim();
    const rows = document.querySelectorAll("#dataTable tbody tr");

    rows.forEach(function(row) {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(keyword) ? "" : "none";
    });
}

/* SEARCH MANUAL */
searchInput.addEventListener("keyup", function() {
    filterTable(this.value);
});

/* REFRESH */
refreshButton.addEventListener("click", function() {
    searchInput.value = "";
    const rows = document.querySelectorAll("#dataTable tbody tr");
    rows.forEach(function(row) {
        row.style.display = "";
    });
});
</script>

{{-- RFID REALTIME SCRIPT --}}
<script>
let lastRFID = null;

function loadRFID() {
    fetch('/admin/latest-rfid')
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data && data.uid_rfid) {
                const uid = String(data.uid_rfid).trim();
                document.getElementById('rfidUID').innerText = uid;

                /* Hanya filter jika UID berubah */
                if (lastRFID !== uid) {
                    lastRFID = uid;
                    searchInput.value = uid; /* Masukkan UID ke pencarian */
                    filterTable(uid); /* Filter tabel */
                }
            }
        })
        .catch(function(error) {
            console.error('RFID Error:', error);
        });
}

/* POLLING SETIAP 2 DETIK */
setInterval(loadRFID, 2000);

/* JALANKAN SAAT HALAMAN DIBUKA */
loadRFID();
</script>

{{-- POPUP HAPUS SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-hapus').forEach(function(button) {
        button.addEventListener('click', function() {
            const form = this.closest('.form-hapus');

            Swal.fire({
                title: 'Hapus Data RFID?',
                text: 'Data RFID yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection