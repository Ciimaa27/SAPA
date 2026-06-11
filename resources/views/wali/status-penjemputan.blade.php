@extends('layouts.app')

@section('title', 'Status Penjemputan')

@section('sidebar')
    @include('layouts.sidebar-wali')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/wali/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/wali/status-penjemputan.css') }}">
@endpush

@section('content')

<div class="main">

    <div class="page-title">
        Status penjemputan anak
    </div>

    <div class="status-alert success">
        <span>!</span>
        Sudah di jemput
    </div>

    <div class="timeline">

        @foreach($riwayat as $item)

        <div class="timeline-item {{ $loop->first ? 'active' : '' }}">

            <div class="timeline-dot"></div>

            <div class="timeline-content">
                <strong>{{ $item['jam'] }}</strong>
                {{ $item['status'] }}
            </div>

        </div>

        @endforeach

    </div>

    </div>

@endsection
