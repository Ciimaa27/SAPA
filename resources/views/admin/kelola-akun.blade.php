@extends('layouts.app')
@section('title', 'Kelola Akun')
@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/kelola-akun.css') }}">

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

    /* 🔥 POPUP STYLE */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.45);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .modal-box {
        background: #fff;
        padding: 25px;
        width: 380px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        animation: fadeIn 0.2s ease-in-out;
    }

    .modal-box h4 {
        margin-bottom: 10px;
        font-weight: 600;
    }

    .modal-box p {
        font-size: 14px;
        color: #555;
    }

    .modal-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .modal-actions .btn {
        width: 48%;
        border-radius: 8px;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

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
            <div class="d-flex align-items-center gap-3 flex-wrap">

                <div style="min-width:140px;">
                    Total akun : <strong>{{ $total }}</strong>
                </div>

                <div style="width:180px;">
                    <select class="form-select form-select-sm">
                        <option>Semua</option>
                        <option>Admin</option>
                        <option>Orangtua / Wali</option>
                        <option>Kepala Sekolah</option>
                    </select>
                </div>

                <a href="/kelola-akun/create" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah
                </a>

                <div class="input-group input-group-sm search-flex ms-auto" style="min-width:260px;">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="searchInputAkun" class="form-control" placeholder="Pencarian">
                </div>

            </div>
        </div>

        <div class="card">
            <div class="table-container">
                <table class="table table-hover align-middle mb-0" id="dataTableAkun">
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
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->name ?? $user->nama_lengkap ?? '-' }}</td>
                            <td>{{ ucfirst($user->nama_role ?? '-') }}</td>
                            <td>{{ $user->email ?? '-' }}</td>
                            <td>

                                <a href="{{ route('kelola-akun.edit') }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <!-- ✅ DELETE -->
                                <form action="{{ route('kelola-akun.destroy', $user->id_user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- 🔥 POPUP -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-box">
        <h4>Hapus</h4>
        <p>
            Yakin ingin menghapus data? data yang di hapus tidak dapat di kembalikan atau di batalkan
        </p>

        <div class="modal-actions">
            <button id="confirmDelete" class="btn btn-danger">Hapus</button>
            <button id="cancelDelete" class="btn btn-secondary">Batal</button>
        </div>
    </div>
</div>

<!-- 🔍 SEARCH -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let input = document.getElementById("searchInputAkun");

    input.addEventListener("keyup", function() {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll("#dataTableAkun tbody tr");

        rows.forEach(function(row) {
            let text = row.textContent.toLowerCase();

            if (text.includes(keyword)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
});
</script>

<!-- 🔥 POPUP SCRIPT -->
<script>
let selectedForm = null;

document.querySelectorAll(".btn-delete").forEach(function(btn){
    btn.addEventListener("click", function(){
        selectedForm = this.closest("form");
        document.getElementById("deleteModal").style.display = "flex";
    });
});

document.getElementById("cancelDelete").addEventListener("click", function(){
    document.getElementById("deleteModal").style.display = "none";
});

document.getElementById("confirmDelete").addEventListener("click", function(){
    if(selectedForm){
        selectedForm.submit();
    }
});
</script>

@endsection
