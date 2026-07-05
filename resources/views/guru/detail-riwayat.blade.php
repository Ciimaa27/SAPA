@extends('layouts.app')

@section('title', 'Detail Riwayat Penjemputan')

@section('sidebar')
    @include('layouts.sidebar-guru')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/guru/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/guru/riwayat.css') }}">
<style>
    /* Tambahan style opsional untuk merapikan halaman detail */
    .detail-info-table th {
        width: 30%;
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('content')
<div class="main-dashboard">
    <div class="container-dashboard">

        <div class="card mb-3 p-3 d-flex flex-row justify-content-between align-items-center">
            <h5 class="mb-0">Detail Penjemputan</h5>
            <a href="{{ route('guru.riwayat') }}"
   class="btn btn-sm btn-secondary">
        </div>

        <div class="card p-4">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    
                    <h6 class="text-muted border-bottom pb-2 mb-3">Informasi Penjemputan</h6>
                    
                    <table class="table table-bordered detail-info-table align-middle">
                        <tr>
                            <th>Waktu Scan</th>
                            <td>{{ $log['waktu'] }}</td>
                        </tr>
                        <tr>
                            <th>ID Scan</th>
                            <td class="fw-bold text-primary">{{ $log['id_scan'] }}</td>
                        </tr>
                        <tr>
                            <th>Nama Penjemput</th>
                            <td>{{ $log['nama'] }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Perangkat / Alat</th>
                            <td>{{ $log['alat'] }}</td>
                        </tr>
                        <tr>
                            <th>Peran</th>
                            <td>{{ $log['peran'] }}</td>
                        </tr>
                        <tr>
                            <th>Status Penjemputan</th>
                            <td>
                                <span class="badge-status {{ $log['status'] == 'Berhasil' ? 'success' : 'danger' }}">
                                    {{ $log['status'] }}
                                </span>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection