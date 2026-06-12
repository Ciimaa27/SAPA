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

        <div class="page-title-card">
            <h5>Jadwal Pulang</h5>
        </div>

        <div class="class-card">
            <div class="class-tabs">
                @for($i = 1; $i <= 6; $i++)
                    <a href="{{ route('jadwal-pulang', ['kelas' => $i]) }}"
                       class="btn-kelas {{ ($activeKelas ?? 1) == $i ? 'active' : '' }}">
                        Kelas {{ $i }}
                    </a>
                @endfor
            </div>
        </div>

        <div class="schedule-card">

            <div class="schedule-card-top">
                <span class="text-muted">
                    Klik ikon pensil untuk mengubah jadwal
                </span>
            </div>

            <div class="jadwal-list">
                @foreach($jadwal as $item)

                <div class="jadwal-row">

                    <div class="jadwal-hari">
                        {{ $item['hari'] }}
                    </div>

                    <div class="jadwal-jam">
                        {{ $item['libur'] ? 'Libur' : $item['jam'] }}
                    </div>

                    <a href="{{ route('jadwal-pulang.edit', [
                            'kelas' => $activeKelas ?? 1,
                            'hari' => $item['hari']
                        ]) }}"
                       class="btn-row-edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>

                </div>

                @endforeach
            </div>

        </div>

    </div>
</div>

@endsection
