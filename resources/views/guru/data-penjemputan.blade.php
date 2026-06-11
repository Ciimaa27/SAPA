@extends('layouts.app')

@section('title', 'Data Penjemputan')

@section('sidebar')
    @include('layouts.sidebar-guru')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/guru/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/guru/data-penjemputan.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- TITLE -->
        <div class="card-box">
            <h5 class="page-title">Data penjemputan</h5>
        </div>

        <!-- SEARCH + TABLE -->
        <div class="card-box">

            <!-- SEARCH -->
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text bg-white">
                    <i class="fa fa-search"></i>
                </span>
                <input type="text" id="searchData" class="form-control" placeholder="Pencarian">
            </div>

            <!-- TABLE -->
            <div class="table-container">
                <table class="table-custom" id="tableData">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th>Wali kelas</th>
                            <th>Jumlah siswa</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($data as $row)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                            <td>{{ $row['kelas'] }}</td>
                            <td>{{ $row['wali'] }}</td>
                            <td>{{ $row['jumlah'] }}</td>
                            <td>
                                <a href="{{ route('guru.penjemputan', $row['id_kelas']) }}"
                                   class="btn btn-success btn-sm"
                                   title="Lihat status">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data kelas</td>
                        </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

            <div class="p-3 d-flex justify-content-end">
                @if ($data->hasPages())
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
                @endif
            </div>

        </div>

    </div>
</div>

<script>
document.getElementById("searchData").addEventListener("keyup", function() {
    let keyword = this.value.toLowerCase();
    let rows = document.querySelectorAll("#tableData tbody tr");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(keyword) ? "" : "none";
    });
});
</script>

@endsection
