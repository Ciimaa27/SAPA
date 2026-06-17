@extends('layouts.app')

@section('title', 'Arsip Siswa')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
@endpush

@section('content')

<div class="main-dashboard">
    <div class="container-dashboard">

        <div class="card mb-3 p-3">
            <h5 class="mb-0">Arsip Siswa</h5>
        </div>

        <div class="card">

            <div class="table-responsive p-3">

                <div class="row">

                    @forelse($tahunArsip as $tahun)

                        <div class="col-md-4 mb-3">
                            <a href="{{ route('arsip-siswa.tahun', ['tahun' => $tahun->tahun_lulus,'status' => $tahun->status]) }}"
                            
                            class="text-decoration-none">

                                <div class="card shadow-sm h-100">
                                    <div class="card-body">

                                        <h5 class="mb-2">
                                            📁 Tahun {{ $tahun->tahun_lulus }}
                                        </h5>

                                        <div class="mb-2">

                                            @if($tahun->status == 'lulus')
                                                <span class="badge bg-success">
                                                    Lulus
                                                </span>

                                            @elseif($tahun->status == 'pindah')
                                                <span class="badge bg-warning text-dark">
                                                    Pindah
                                                </span>

                                            @elseif($tahun->status == 'mengundurkan_diri')
                                                <span class="badge bg-danger">
                                                    Mengundurkan Diri
                                                </span>
                                            @endif

                                        </div>

                                        <small class="text-muted">
                                            {{ $tahun->total }} siswa
                                        </small>

                                    </div>
                                </div>

                            </a>
                        </div>

                    @empty

                        <div class="text-center">
                            Belum ada arsip.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>
</div>

@endsection