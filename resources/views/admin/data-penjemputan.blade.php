@extends('layouts.app')

@section('title', 'Data Penjemputan')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/data-penjemputan.css') }}">
@endpush

@section('content')
<div class="main-dashboard">
    <div class="container-dashboard">
        {{-- Judul --}}
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Data Penjemputan</h5>
        </div>

        {{-- Card Table --}}
        <div class="card p-4">
                    {{-- Search --}}
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white">
                            <i class="fa fa-search"></i>
                        </span>

                        <input type="text"
                            id="searchInput"
                            class="form-control"
                            placeholder="Pencarian">
                    </div>

            <div class="table-container">
                <table class="table align-middle mb-0" id="kelasTable">
                    <thead>
                        <tr>
                            <th width="70" class="text-center">No</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-start">Wali kelas</th>
                            <th class="text-center">Jumlah siswa</th>
                            <th width="170" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas as $item)
                            <tr>
                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="text-center">
                                    {{ $item->nama_kelas }}
                                </td>
                                <td class="text-start ps-4">
                                    {{ $item->nama_guru }}
                                </td>
                                <td class="text-center">
                                    {{ $item->jumlah_siswa }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('data-penjemputan.status', $item->id_kelas) }}" class="btn-status">
                                        Lihat Status
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    Tidak ada data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const search = document.getElementById("searchInput");

    search.addEventListener("keyup", function() {
        let value = this.value.toLowerCase();

        document.querySelectorAll("#kelasTable tbody tr").forEach(function(row) {
            row.style.display = row.innerText.toLowerCase().includes(value)
                ? ""
                : "none";
        });
    });
</script>
@endsection
