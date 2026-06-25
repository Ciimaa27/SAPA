@extends('layouts.app')

@section('title','RFID & Sidik Jari')

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
            <h5 class="mb-0">RFID dan Sidik jari</h5>
        </div>

        <!-- ✅ ALERT -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- TAB -->
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                <a href="{{ route('iot.index',['tab'=>'rfid']) }}"
                   class="btn btn-tab {{ ($tab ?? 'rfid')=='rfid' ? 'active' : '' }}">
                   RFID
                </a>

                <a href="{{ route('iot.index',['tab'=>'sidik-jari']) }}"
                   class="btn btn-tab {{ ($tab ?? '')=='sidik-jari' ? 'active' : '' }}">
                   Sidik jari
                </a>

                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Pencarian">
                </div>

            </div>
        </div>

        <!-- 🔥 RFID REALTIME -->
        @if($tab == 'rfid')
        <div class="card p-3 mb-3 text-center">
            <h5>Scan RFID Terakhir</h5>
            <h3 id="rfidUID" style="font-weight:bold; color:#6f42c1;">
                Menunggu scan...
            </h3>
        </div>
        @else
        <!-- 🔥 FINGERPRINT REALTIME -->
        <div class="card p-3 mb-3 text-center">
            <h5>Scan Sidik Jari Terakhir</h5>
            <h3 id="fingerprintID" style="font-weight:bold; color:#6f42c1;">
                Menunggu scan...
            </h3>
        </div>
        @endif

        <!-- TABLE -->
        <div class="card">

            <!-- TABLE -->
            <div class="table-container">
                <table class="table table-hover align-middle mb-0" id="dataTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>

                            @if($tab == 'rfid')
                                <th>Nama Siswa</th>
                                <th>UID</th>
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
                                <td>{{ $item->rfid_uid ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('iot.destroy',['tab'=>'rfid','id'=>$item->id_siswa]) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin hapus?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @else
                                <td>{{ $item->nama_wali }}</td>
                                <td>{{ $item->fingerprint_id ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('iot.destroy',['tab'=>'sidik-jari','id'=>$item->id_wali]) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin hapus?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endif

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
                <nav>
                    <ul class="pagination pagination-sm mb-0">

                        {{-- Previous --}}
                        @if ($data->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">‹</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $data->previousPageUrl() }}">‹</a>
                            </li>
                        @endif

                        {{-- Numbers --}}
                        @php
                            $current = $data->currentPage();
                            $last = $data->lastPage();
                        @endphp

                        {{-- First page --}}
                        @if ($current > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $data->url(1) }}">1</a>
                            </li>

                            @if ($current > 4)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                        @endif

                        {{-- Middle pages --}}
                        @for ($i = max(1, $current - 1); $i <= min($last, $current + 1); $i++)
                            <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                <a class="page-link" href="{{ $data->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Last page --}}
                        @if ($current < $last - 2)
                            @if ($current < $last - 3)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif

                            <li class="page-item">
                                <a class="page-link" href="{{ $data->url($last) }}">{{ $last }}</a>
                            </li>
                        @endif

                        {{-- Next --}}
                        @if ($data->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $data->nextPageUrl() }}">›</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">›</span>
                            </li>
                        @endif

                    </ul>
                </nav>
            </div>

        </div>

    </div>
</div>

{{-- SEARCH --}}
<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
    let keyword = this.value.toLowerCase();
    let rows = document.querySelectorAll("#dataTable tbody tr");

    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(keyword) ? "" : "none";
    });
});
</script>

{{-- 🔥 RFID & FINGERPRINT REALTIME --}}
<script>
function loadRFID(){
    fetch('/admin/latest-rfid')
    .then(res => res.json())
    .then(data => {
        if(data && data.uid_rfid){
            document.getElementById('rfidUID').innerText = data.uid_rfid;
        }
    });
}

function loadFingerprint(){
    fetch('/admin/latest-fingerprint')
    .then(res => res.json())
    .then(data => {
        if(data && data.fingerprint_id){
            document.getElementById('fingerprintID').innerText = data.fingerprint_id;
        }
    });
}

@if($tab == 'rfid')
    setInterval(loadRFID, 2000);
    loadRFID();
@else
    setInterval(loadFingerprint, 2000);
    loadFingerprint();
@endif
</script>

@endsection
