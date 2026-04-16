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

                        @php
                        $data = [
                            ['waktu'=>'07:01:22','id'=>'03HGU98','nama'=>'Arif Nasution','alat'=>'RFID','peran'=>'Siswa','status'=>'Berhasil'],
                            ['waktu'=>'07:15:08','id'=>'09HH7XE','nama'=>'Radita Nabila','alat'=>'RFID','peran'=>'Siswa','status'=>'Berhasil'],
                            ['waktu'=>'10:35:44','id'=>'014KMNL','nama'=>'Indah Permatasari','alat'=>'Fingerprint','peran'=>'Orangtua/wali','status'=>'Gagal'],
                        ];
                        @endphp

                        @foreach($data as $row)
                        <tr>
                            <td>{{ $row['waktu'] }}</td>
                            <td>{{ $row['id'] }}</td>

                            <!-- 🔥 FIX: nama bukan link -->
                            <td>{{ $row['nama'] }}</td>

                            <td>{{ $row['alat'] }}</td>
                            <td>{{ $row['peran'] }}</td>

                            <td>
                                <span class="badge-status {{ $row['status'] == 'Berhasil' ? 'success' : 'danger' }}">
                                    {{ $row['status'] }}
                                </span>
                            </td>

                            <!-- 🔥 DETAIL BUTTON -->
                            <td>
                                <button class="btn-detail">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </td>

                        </tr>
                        @endforeach

                    </tbody>

                </table>
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
