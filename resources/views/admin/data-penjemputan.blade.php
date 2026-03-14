@extends('layouts.app')

@section('title', 'Data Penjemputan')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/admin/data-penjemputan.css') }}">

    @include('layouts.sidebar-admin')
    @include('layouts.topbar')

    <div class="main-dashboard">
        <div class="container-dashboard">

            <div class="card mb-3 p-3">
                <h5 class="mb-0">Data Penjemputan</h5>
            </div>

            <div class="card mb-3 p-3">
                <div class="d-flex align-items-center gap-2">
                    <input type="date" class="form-control form-control-sm" style="width:160px">

                    <select class="form-select form-select-sm" style="width:140px">
                        <option>Kelas</option>
                        <option>1-A</option>
                        <option>1-B</option>
                        <option>2-A</option>
                        <option>2-B</option>
                    </select>

                    <input type="text" class="form-control form-control-sm" placeholder="cari..." style="width:150px">

                    <button class="btn btn-danger btn-sm">
                        <i class="fa fa-rotate-left"></i>
                    </button>
                </div>
            </div>

            <div class="card p-5 text-center text-muted">
                Pilih data yang ingin di tampilkan
            </div>

        </div>
    </div>

@endsection
