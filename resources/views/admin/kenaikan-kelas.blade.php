@extends('layouts.app')

@section('title', 'Kenaikan Kelas')

{{-- 🔥 SIDEBAR --}}
@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

{{-- 🔥 CSS --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<style>
    .card {
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .btn-promo {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
    }

    .btn-promo:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        color: white;
        text-decoration: none;
    }

    .form-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .table-container {
        max-height: 500px;
        overflow-y: auto;
    }

    .table thead th {
        position: sticky;
        top: 0;
        background: #f8f9fa;
        z-index: 2;
    }

    .checkbox-cell {
        width: 50px;
    }

    .alert {
        margin-bottom: 15px;
    }
</style>
@endpush

{{-- 🔥 CONTENT --}}
@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <!-- HEADER -->
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Kenaikan Kelas</h5>
        </div>

        <!-- ✅ ALERT -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- FILTER FORM -->
        <div class="card mb-3 p-3">
            <form method="GET" action="{{ route('kenaikan-kelas') }}" class="form-section">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Kelas Saat Ini</label>
                        <select name="kelas_dari" class="form-select form-select-sm">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ request('kelas_dari') == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Kelas Tujuan</label>
                        <select name="kelas_ke" class="form-select form-select-sm">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ request('kelas_ke') == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fa fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- TABLE -->
        <form method="POST" action="{{ route('kenaikan-kelas.store') }}">
            @csrf

            <div class="card">
                <div class="table-container">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="checkbox-cell">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas Saat Ini</th>
                                <th>Kelas Tujuan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($siswa as $item)
                            <tr>
                                <td class="checkbox-cell">
                                    <input type="checkbox" name="siswa_id[]" value="{{ $item->id_siswa }}" class="student-checkbox">
                                </td>
                                <td>{{ $item->nis }}</td>
                                <td>{{ $item->nama_siswa }}</td>
                                <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                                <td>
                                    <select name="kelas_tujuan[{{ $item->id_siswa }}]" class="form-select form-select-sm">
                                        <option value="">-- Pilih --</option>
                                        @foreach($kelas as $k)
                                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data siswa</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- ACTION BUTTON -->
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            <span id="selectedCount">0</span> siswa terpilih
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('data-siswa') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn-promo btn-sm" onclick="return confirm('Yakin melakukan kenaikan kelas untuk siswa yang dipilih?')">
                            <i class="fa fa-arrow-up"></i> Naikan Kelas
                        </button>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>

<script>
// Select All Checkbox
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.student-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedCount();
});

// Update selected count
document.querySelectorAll('.student-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

function updateSelectedCount() {
    const count = document.querySelectorAll('.student-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = count;
}

// Initial count
updateSelectedCount();
</script>

@endsection
