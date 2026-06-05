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
            <h5 class="mb-0">Arsip Siswa Lulus</h5>
        </div>

        <div class="card mb-3 p-3">
            <div class="d-flex align-items-center gap-3">

                <div>
                    Total Arsip :
                    <strong>{{ $total }}</strong>
                </div>

                <form method="GET"
                      action="{{ route('arsip-siswa') }}"
                      style="flex:1; max-width:400px;">

                    <div class="input-group input-group-sm">

                        <span class="input-group-text">
                            <i class="fa fa-search"></i>
                        </span>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Cari siswa...">

                    </div>
                </form>

            </div>
        </div>

        <div class="card">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas Terakhir</th>
                            <th>Jenis Kelamin</th>
                            <th>Tahun Lulus</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($arsip as $item)

                        <tr>
                            <td>{{ $item->nis }}</td>
                            <td>{{ $item->nama_siswa }}</td>
                            <td>{{ $item->kelas_terakhir }}</td>
                            <td>{{ $item->jenis_kelamin }}</td>
                            <td>{{ $item->tahun_lulus }}</td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="5" class="text-center">
                                Belum ada data arsip.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="p-3">
                {{ $arsip->links() }}
            </div>

        </div>

    </div>
</div>

@endsection