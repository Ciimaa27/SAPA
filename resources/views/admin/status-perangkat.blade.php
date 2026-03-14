@extends('layouts.app')

@section('title','Status perangkat dan log aktivitas')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/status-perangkat.css') }}">

    @include('layouts.sidebar-admin')
    @include('layouts.topbar')

    <div class="main-dashboard">
        <div class="container-dashboard">

            <div class="card mb-3 p-3">
                <h5 class="mb-0">Status perangkat dan log aktivitas</h5>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card p-3 status-card">
                        <h6 class="mb-2">RFID Reader</h6>

                        <div class="d-flex justify-content-between">
                            <span>Status</span>
                            <span class="status-offline">
                                ● Offline
                            </span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>IP Address</span>
                            <span>198.168.1.10</span>
                        </div>

                        <div class="text-danger small mt-2">
                            Terakhir aktif : 16 feb 2026, 08:22
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-3 status-card">
                        <h6 class="mb-2">RFID Reader</h6>

                        <div class="d-flex justify-content-between">
                            <span>Status</span>
                            <span class="status-online">
                                ● Online
                            </span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>IP Address</span>
                            <span>198.168.1.11</span>
                        </div>

                        <div class="text-danger small mt-2">
                            Terakhir aktif : 16 feb 2026, 10:45
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="p-3">
                    <h6 class="mb-0">Log aktivitas perangkat</h6>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Waktu</th>
                                <th>ID Scan</th>
                                <th>Nama</th>
                                <th>Jenis perangkat</th>
                                <th>Peran</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>07:01:22</td>
                                <td>03HGU98</td>
                                <td>Arif Nasution</td>
                                <td>RFID</td>
                                <td>Siswa</td>
                                <td>
                                    <span class="badge bg-success">
                                        Berhasil
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td>07:15:08</td>
                                <td>09HH7XE</td>
                                <td>Radita Nabila</td>
                                <td>RFID</td>
                                <td>Siswa</td>
                                <td>
                                    <span class="badge bg-success">
                                        Berhasil
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td>10:35:44</td>
                                <td>014KMNL</td>
                                <td>Indah Permatasari</td>
                                <td>Fingerprint</td>
                                <td>Orangtua/wali</td>
                                <td>
                                    <span class="badge bg-danger">
                                        Gagal
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection
