@extends('layouts.app')

@section('title', 'Sidik jari')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/rfid.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <div class="card mb-3 p-3">
            <h5 class="mb-0">RFID dan Sidik jari</h5>
        </div>

        <!-- TAB -->
        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                <a href="{{ route('iot.index', ['tab'=>'rfid']) }}"
                   class="btn btn-tab {{ ($tab ?? '') === 'rfid' ? 'active' : '' }}">
                   RFID
                </a>

                <a href="{{ route('iot.index', ['tab'=>'sidik-jari']) }}"
                   class="btn btn-tab {{ ($tab ?? '') === 'sidik-jari' ? 'active' : '' }}">
                   Sidik jari
                </a>

                <div class="input-group input-group-sm search-flex">
                    <span class="input-group-text bg-white">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Pencarian">
                </div>

            </div>
        </div>

        <!-- TABLE -->
        <div class="card">
            <div class="d-flex justify-content-end p-3">
                <a href="{{ route('iot.store', ['tab'=>'sidik-jari']) }}" class="btn btn-primary btn-sm btn-tambah">
                    <i class="fa fa-plus"></i> Tambah
                </a>
            </div>

            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Wali</th>
                            <th>ID Fingerprint</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_wali }}</td>
                            <td>{{ $item->fingerprint_id ?? '-' }}</td>
                            <td>
                                <form action="{{ route('iot.destroy', ['tab'=>'sidik-jari','id'=>$item->id_wali]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                        @if(count($data) === 0)
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data sidik jari</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>

@endsection
