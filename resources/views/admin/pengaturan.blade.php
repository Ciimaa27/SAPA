@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/pengaturan.css') }}">
@endpush

@section('content')

<div class="main-dashboard">

    <div class="container-dashboard">
        <div class="page-header">
            <h2>Pengaturan Sistem</h2>
        </div>
        <div class="setting-card">

            @if(session('success'))

                <div class="alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>

            @endif

            <form action="{{ route('admin.pengaturan.update') }}" method="POST">

                @csrf

                <div class="status-wrapper">

                    <label class="status-card">

                        <input type="radio"
                               name="status_sistem"
                               value="aktif"
                               {{ $pengaturan->status_sistem == 'aktif' ? 'checked' : '' }}>

                        <div class="status-content">

                            <div class="status-icon active">

                                <i class="fa-solid fa-circle-check"></i>

                            </div>

                            <div>

                                <h4>Aktif</h4>

                                <p>
                                    Sistem dapat digunakan untuk proses absensi
                                    dan penjemputan siswa.
                                </p>

                            </div>

                        </div>

                    </label>


                    <label class="status-card">

                        <input type="radio"
                               name="status_sistem"
                               value="nonaktif"
                               {{ $pengaturan->status_sistem == 'nonaktif' ? 'checked' : '' }}>

                        <div class="status-content">

                            <div class="status-icon nonaktif">

                                <i class="fa-solid fa-circle-xmark"></i>

                            </div>

                            <div>

                                <h4>Nonaktif</h4>

                                <p>
                                    Sistem tidak menerima proses absensi maupun
                                    penjemputan.
                                </p>

                            </div>

                        </div>

                    </label>

                </div>

                <button type="submit" class="btn-save">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Simpan Pengaturan

                </button>

            </form>

        </div>

    </div>

</div>

@endsection