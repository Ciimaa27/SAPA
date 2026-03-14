@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/data-siswa.css') }}">

    @include('layouts.sidebar-admin')
    @include('layouts.topbar')

    <div class="main-dashboard">
        <div class="container-dashboard">

            <div class="card mb-3 p-3">
                <h5 class="mb-0">Data siswa</h5>
            </div>

            <div class="card mb-3 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div style="min-width:140px;">
                        Total Siswa : <strong>501</strong>
                    </div>

                    <div style="width:200px;">
                        <select class="form-select form-select-sm">
                            <option>Tampilkan</option>
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                    </div>

                    <a href="#" class="btn btn-primary btn-sm btn-tambah">
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

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>NIS</th>
                                <th>Nama lengkap</th>
                                <th>Kelas</th>
                                <th>Jenis Kelamin</th>
                                <th>Tempat/Tanggal lahir</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>00987643</td>
                                <td>Arif Nasution</td>
                                <td>1-A</td>
                                <td>Laki-laki</td>
                                <td>Banjarmasin, 02-10-2010</td>
                                <td>
                                    <button class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>00985651</td>
                                <td>Radita Nabila</td>
                                <td>1-A</td>
                                <td>Perempuan</td>
                                <td>Banjarmasin, 15-02-2010</td>
                                <td>
                                    <button class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>00952763</td>
                                <td>Arif Rahman</td>
                                <td>1-B</td>
                                <td>Laki-laki</td>
                                <td>Banjarbaru, 24-03-2010</td>
                                <td>
                                    <button class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </button>
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
