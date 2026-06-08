@extends('layouts.app')

@section('title','Sidik Jari')

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

        <!-- SEARCH -->
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Pencarian">
                </div>
            </div>
        </div>

        <!-- 🔥 FINGERPRINT REALTIME -->
        <div class="card p-3 mb-3 text-center">
            <h5>Scan Sidik Jari Terakhir</h5>
            <h3 id="fingerprintID" style="font-weight:bold; color:#6f42c1;">
                Menunggu scan...
            </h3>
        </div>

        <!-- TABLE -->
        <div class="card">

            <!-- BUTTON TAMBAH -->
            <div class="d-flex justify-content-end p-3">
                <a href="{{ route('tambah-data-sidik-jari') }}" class="btn-tambah-rfid">
                    Tambah
                    <span class="icon-plus">+</span>
                </a>
            </div>

            <!-- TABLE -->
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
                                <form action="{{ route('iot.destroy',['tab'=>'sidik-jari','id'=>$item->id_wali]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
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

{{-- 🔥 FINGERPRINT REALTIME --}}
<script>
function loadFingerprint(){
    fetch('/admin/latest-fingerprint')
    .then(res => res.json())
    .then(data => {
        if(data && data.fingerprint_id){
            document.getElementById('fingerprintID').innerText = data.fingerprint_id;
        }
    });
}

setInterval(loadFingerprint, 2000);
loadFingerprint();
</script>

@endsection
