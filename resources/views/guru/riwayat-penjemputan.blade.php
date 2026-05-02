@extends('layouts.app')

@section('title', 'Riwayat Penjemputan')

@section('sidebar')
    @include('layouts.sidebar-guru')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/guru/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/guru/riwayat.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- TITLE -->
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Penjemputan</h5>
        </div>

        <!-- SEARCH + TABLE -->
        <div class="card p-3">

            <!-- SEARCH -->
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text bg-white">
                    <i class="fa fa-search"></i>
                </span>
                <input type="text" id="searchPenjemputan" class="form-control" placeholder="Pencarian">
            </div>

            <!-- TABLE -->
            <div class="table-container">
                <table class="table table-hover align-middle mb-0" id="tablePenjemputan">

                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>ID Scan</th>
                            <th>Nama</th>
                            <th>Jenis perangkat</th>
                            <th>Peran</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($logs as $row)
                        <tr>
                            <td>{{ $row['waktu'] }}</td>
                            <td>{{ $row['id_scan'] }}</td>
                            <td>{{ $row['nama'] }}</td>
                            <td>{{ $row['alat'] }}</td>
                            <td>{{ $row['peran'] }}</td>
                            <td>
                                <span class="badge-status {{ $row['status'] == 'Berhasil' ? 'success' : 'danger' }}">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                            <td>
                                <button class="btn-detail">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada riwayat penjemputan</td>
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
                        @if ($logs->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">‹</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $logs->previousPageUrl() }}">‹</a>
                            </li>
                        @endif

                        {{-- Numbers --}}
                        @php
                            $current = $logs->currentPage();
                            $last = $logs->lastPage();
                        @endphp

                        {{-- First page --}}
                        @if ($current > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $logs->url(1) }}">1</a>
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
                                <a class="page-link" href="{{ $logs->url($i) }}">{{ $i }}</a>
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
                                <a class="page-link" href="{{ $logs->url($last) }}">{{ $last }}</a>
                            </li>
                        @endif

                        {{-- Next --}}
                        @if ($logs->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $logs->nextPageUrl() }}">›</a>
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
document.getElementById("searchPenjemputan").addEventListener("keyup", function() {
    let keyword = this.value.toLowerCase();
    let rows = document.querySelectorAll("#tablePenjemputan tbody tr");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(keyword) ? "" : "none";
    });
});
</script>

@endsection
