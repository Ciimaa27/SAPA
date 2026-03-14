@extends('layouts.app')

@section('title', 'Guru dan Kelas')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/guru.css') }}">

    @include('layouts.sidebar-admin')
    @include('layouts.topbar')

    <div class="main-dashboard">
        <div class="container-dashboard">

            <div class="card mb-3 p-3">
                <h5 class="mb-0">Guru dan Kelas</h5>
            </div>

            <div class="card mb-3 p-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('guru') }}" class="btn btn-tab">
                        Guru
                    </a>

                    <a href="{{ route('kelas') }}" class="btn btn-tab active">
                        Kelas
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
                <div class="d-flex justify-content-end p-3">
                    <a href="#" class="btn btn-primary btn-sm btn-tambah">
                        <i class="fa fa-plus"></i> Tambah kelas
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kelas</th>
                                <th>Wali kelas</th>
                                <th>Jumlah siswa</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>1-A</td>
                                <td>Arif Nasution</td>
                                <td>25</td>
                                <td>
                                    <a href="{{ route('siswa-kelas') }}" class="btn btn-success btn-sm">
                                Lihat siswa
                                </a>
                                </td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>1-A</td>
                                <td>Radita Nabila</td>
                                <td>21</td>
                                <td>
                                    <button class="btn btn-success btn-sm">
                                        Lihat siswa
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>1-B</td>
                                <td>Arif Rahman</td>
                                <td>21</td>
                                <td>
                                    <button class="btn btn-success btn-sm">
                                        Lihat siswa
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>4</td>
                                <td>1-B</td>
                                <td>Ismatul Hawa</td>
                                <td>22</td>
                                <td>
                                    <button class="btn btn-success btn-sm">
                                        Lihat siswa
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
