@extends('layouts.app')
@section('title', 'Kelola Akun')
@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/kelola-akun.css') }}">

    @include('layouts.sidebar-admin')
    @include('layouts.topbar')

    <div class="main-dashboard">
        <div class="container-dashboard">

            <div class="card mb-3 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Kelola akun pengguna</h5>
                </div>
            </div>

            <div class="card mb-3 p-3">
                <div class="d-flex align-items-center gap-3">

                    <div style="min-width:140px;">
                        Total akun : <strong>501</strong>
                    </div>

                    <div style="width:200px;">
                        <select class="form-select form-select-sm">
                            <option>Semua</option>
                            <option>Admin</option>
                            <option>Orangtua / Wali</option>
                            <option>Kepala Sekolah</option>
                        </select>
                    </div>

                    <a href="{{ route('tambah-akun') }}" class="btn btn-primary btn-sm btn-tambah">
                        <i class="fa fa-plus"></i> Tambah
                    </a>

                    <div class="input-group input-group-sm search-flex">
                        <span class="input-group-text bg-white">
                            <i class="fa fa-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Pencarian">
                    </div>

                </div>

                </div>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama pengguna</th>
                                <th>Nama lengkap</th>
                                <th>Peran</th>
                                <th>Email</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>admin01</td>
                                <td>Arif Nasution</td>
                                <td>Admin</td>
                                <td>admin01@sekolah.id</td>
                                <td>
                                    <button class="btn btn-warning btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>admin02</td>
                                <td>Radita Nabila</td>
                                <td>Admin</td>
                                <td>admin02@sekolah.id</td>
                                <td>
                                    <button class="btn btn-warning btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>guru_arif</td>
                                <td>Arif Rahman</td>
                                <td>Guru</td>
                                <td>guruarif@sekolah.id</td>
                                <td>
                                    <button class="btn btn-warning btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end p-3">
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item disabled">
                                <a class="page-link">‹</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link">›</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

        </div>
    </div>

@endsection
