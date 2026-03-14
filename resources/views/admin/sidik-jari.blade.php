@extends('layouts.app')

@section('title','RFID dan Sidik jari')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/rfid.css') }}">

    @include('layouts.sidebar-admin')
    @include('layouts.topbar')

    <div class="main-dashboard">
        <div class="container-dashboard">

            <div class="card mb-3 p-3">
                <h5 class="mb-0">RFID dan Sidik jari</h5>
            </div>

            <div class="card mb-3 p-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('rfid') }}" class="btn btn-tab">RFID</a>
                    <a href="{{ route('sidik-jari') }}" class="btn btn-tab active">Sidik jari</a>

                    <div style="width:170px;">
                        <select class="form-select form-select-sm">
                            <option>Tampilkan</option>
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                    </div>

                    <div class="input-group input-group-sm search-flex">
                        <span class="input-group-text bg-white">
                            <i class="fa fa-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Pencarian">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="d-flex justify-content-end p-3">
                    <a href="#" class="btn btn-primary btn-sm btn-tambah">
                        <i class="fa fa-plus"></i> Tambah
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama siswa</th>
                                <th>ID Fingerprint</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Arif Nasution</td>
                                <td>1120</td>
                                <td>
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>Radita Nabila</td>
                                <td>1121</td>
                                <td>
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>Arif Rahman</td>
                                <td>1122</td>
                                <td>
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection