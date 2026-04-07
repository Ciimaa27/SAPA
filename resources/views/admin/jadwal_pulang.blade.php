@extends('layouts.app')

@section('title','Jadwal Pulang')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/jadwal_pulang.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="main-dashboard">
    <div class="container-dashboard">

        <div class="page-title-card">
            <h5>Jadwal pulang</h5>
        </div>

        <div class="class-card">
            <div class="class-tabs">
                @for($i=1; $i<=6; $i++)
                    <a href="{{ route('jadwal-pulang', ['kelas' => $i]) }}"
                       class="btn-kelas {{ ($activeKelas ?? 1) === $i ? 'active' : '' }}">
                        Kelas {{ $i }}
                    </a>
                @endfor
            </div>
        </div>

        <div class="schedule-card">
            <div class="schedule-card-top">
                <a href="{{ route('jadwal-pulang.edit', ['kelas' => $activeKelas ?? 1]) }}" class="btn-edit">
                    Edit
                </a>
            </div>

            <div class="jadwal-list">
                @foreach($jadwal as $item)
                <div class="jadwal-row">
                    <div class="jadwal-hari">{{ $item['hari'] }}</div>
                    <div class="jadwal-jam">
                        {{ $item['libur'] ? 'Libur' : $item['jam'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

@endsection
