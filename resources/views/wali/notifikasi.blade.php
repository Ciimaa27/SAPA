@extends('layouts.app')

@section('title','Notifikasi')

@section('sidebar')
    @include('layouts.sidebar-wali')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/wali/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/wali/notifikasi.css') }}">
@endpush

@section('content')

<div class="main-dashboard">

    <div class="container-dashboard">

        <div class="page-title-card">
            <h5>Notifikasi</h5>
        </div>

        <div class="notif-card">

            @forelse($notifikasi as $item)
            <div class="notif-item">
                <div class="notif-title">
                    {{ $item['judul'] }}
                </div>

                <div class="notif-time">
                    {{ $item['waktu'] }}
                </div>
            </div>
            @empty
            <div class="notif-item">
                <div class="notif-title">
                    Tidak ada notifikasi
                </div>
            </div>
            @endforelse

        </div>

    </div>

</div>

@endsection
