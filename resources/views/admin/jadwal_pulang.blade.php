@extends('layouts.app')

@section('title','Jadwal Pulang')

@section('sidebar')
    @include('layouts.sidebar-admin')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/sidebar-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/jadwal_pulang.css') }}">
@endpush

@section('content')
<div class="main-dashboard">
    <div class="container-dashboard">

        {{-- HEADER --}}
        <div class="card mb-3 p-3">
            <h5 class="mb-0">Jadwal Pulang</h5>
        </div>

        {{-- PILIH KELAS --}}
        <div class="card mb-3 p-3">
            <div class="kelas-wrapper">
                @for($i = 1; $i <= 6; $i++)
                    <a href="{{ route('jadwal-pulang',['kelas'=>$i]) }}"
                       class="btn-kelas {{ ($activeKelas ?? 1) == $i ? 'active' : '' }}">
                        Kelas {{ $i }}
                    </a>
                @endfor
            </div>
        </div>

        {{-- CARD JADWAL --}}
        <div class="card">
    <div class="card-body">

        <div class="jadwal-info">
            <i class="fa-solid fa-circle-info"></i>
            <span>Klik ikon pensil untuk mengubah jadwal pulang.</span>
        </div>

        <div class="jadwal-scroll">
            <div class="jadwal-container">
                @foreach($jadwal as $item)
                    <div class="jadwal-item">

                        {{-- Hari --}}
                        <div class="hari">
                            {{ $item['hari'] }}
                        </div>

                        {{-- Jam --}}
                        <div class="jam">
                            @if($item['libur'])
                                <span class="badge-libur">Libur</span>
                            @else
                                {{ $item['jam'] }}
                            @endif
                        </div>

                        {{-- Tombol Edit --}}
                        <div class="aksi">
                            <a href="{{ route('jadwal-pulang.edit',[
                                    'kelas' => $activeKelas ?? 1,
                                    'hari'  => $item['hari']
                                ]) }}"
                               class="btn-edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

</div>
@endsection
