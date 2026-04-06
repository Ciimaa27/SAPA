@extends('layouts.app')

@section('title', 'Jadwal Pulang')

@section('content')

<link rel="stylesheet" href="{{ asset('css/admin/jadwal_pulang.css') }}">

@include('layouts.sidebar-admin')
@include('layouts.topbar')

<div class="container mt-4">

    <!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold">Jadwal Pulang</h3>
     <a href="{{ route('jadwal-pulang.edit') }}" class="btn btn-outline-dark btn-sm">
        Edit
    </a>
</div>

    <!-- Tabs -->
    <ul class="nav nav-pills mb-3">
        @for ($i = 1; $i <= 6; $i++)
        <li class="nav-item">
            <a href="#" class="nav-link {{ $i == 1 ? 'active' : '' }}">
                Kelas {{ $i }}
            </a>
        </li>
        @endfor
    </ul>

    <!-- Card -->
    <div class="card p-4 shadow-sm">

        @php
            $jadwal = [
                ['hari' => 'Senin', 'jam' => '10:30'],
                ['hari' => 'Selasa', 'jam' => '10:30'],
                ['hari' => 'Rabu', 'jam' => '10:30'],
                ['hari' => 'Kamis', 'jam' => '10:30'],
                ['hari' => 'Jumat', 'jam' => '09:30'],
                ['hari' => 'Sabtu', 'jam' => '10:30'],
            ];
        @endphp

        @foreach ($jadwal as $item)
        <div class="jadwal-row">
            <div class="hari">
                {{ $item['hari'] }}
            </div>

            <input type="text" class="form-control jam" value="{{ $item['jam'] }}">
        </div>
        @endforeach

    </div>

</div>

@endsection
