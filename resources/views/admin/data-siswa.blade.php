@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/data-siswa.css') }}">

<style>
    .table-container {
        max-height: 400px;
        overflow-y: auto;
    }

    .table thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        z-index: 2;
    }

    .col-aksi {
        width: 150px;
    }

    .alert {
        margin-bottom: 15px;
    }
</style>

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Data siswa</h5>
        </div>

        <!-- FILTER & ACTION -->
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                <div style="min-width:140px;">
                    Total Siswa : <strong>{{ $total }}</strong>
                </div>

                <div style="width:200px;">
                    <select class="form-select form-select-sm">
                        <option>Tampilkan</option>
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                </div>

                <a href="{{ route('tambah-siswa') }}" class="btn btn-sm" style="border: 1px solid #dee2e6; background: transparent; color: #333;">
                    <i class="fa fa-plus"></i> Tambah
                </a>

                <form method="GET" action="{{ route('data-siswa') }}" style="flex: 1; max-width: 500px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border">
                            <i class="fa fa-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Pencarian">
                    </div>
                </form>

            </div>
        </div>

        <!-- ✅ ALERT -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- TABLE -->
        <div class="card">
            <div class="table-container">

                <table class="table table-hover align-middle mb-0" id="dataTable">
                    <thead class="table-light">
                        <tr>
                            <th>NIS</th>
                            <th>Nama lengkap</th>
                            <th>Kelas</th>
                            <th>Jenis Kelamin</th>
                            <th>Tempat/Tanggal lahir</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($siswa as $item)
                        <tr>
                            <td>{{ $item->nis }}</td>
                            <td>{{ $item->nama_siswa }}</td>
                            <td>{{ $item->id_kelas }}</td>
                            <td>{{ $item->jenis_kelamin }}</td>
                            <td>{{ $item->tempat_lahir }}, {{ $item->tanggal_lahir }}</td>

                            <td>
                                <!-- LIHAT -->
                                <a href="{{ route('data-siswa.show', $item->id_siswa) }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <!-- EDIT -->
                                <a href="{{ route('edit-siswa', $item->id_siswa) }}" class="btn btn-warning btn-sm mx-1" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <!-- HAPUS -->
                                <form action="{{ route('hapus-siswa', $item->id_siswa) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            <!-- 🔥 PAGINATION -->
            <div class="p-3 d-flex justify-content-end">
                {{ $siswa->links() }}
            </div>

        </div>

    </div>
</div>

<!-- SCRIPT DELETE SAJA -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const confirmModal = document.getElementById('confirmModal');
    const confirmButton = document.querySelector('.btn-confirm');
    const cancelButton = document.querySelector('.btn-cancel');
    let activeDeleteForm = null;

    document.querySelectorAll('.btn-delete').forEach(function(button) {
        button.addEventListener('click', function() {
            activeDeleteForm = button.closest('.delete-form');
            confirmModal.classList.add('show');
        });
    });

    confirmButton.addEventListener('click', function() {
        if (activeDeleteForm) {
            activeDeleteForm.submit();
        }
    });

    cancelButton.addEventListener('click', function() {
        confirmModal.classList.remove('show');
        activeDeleteForm = null;
    });

    confirmModal.addEventListener('click', function(event) {
        if (event.target === confirmModal || event.target.classList.contains('confirm-modal-backdrop')) {
            confirmModal.classList.remove('show');
            activeDeleteForm = null;
        }
    });
});
</script>

@endsection
