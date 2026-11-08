{{-- Layout dashboard operasional Karyawan. --}}
@extends('layouts.base')

@section('body')
    <nav class="navbar navbar-expand-lg navbar-dark kk-navbar">
        <div class="container-fluid">
            <a class="navbar-brand fw-semibold" href="{{ url('/karyawan') }}">Kopi &amp; Kita <span class="badge text-bg-light ms-1">Karyawan</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-staff" aria-controls="nav-staff" aria-expanded="false" aria-label="Buka menu navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav-staff">
                <ul class="navbar-nav me-auto">
                    @include('partials.nav-staff')
                </ul>
                <ul class="navbar-nav align-items-lg-center">
                    <li class="nav-item"><span class="navbar-text me-2">{{ auth()->user()->nama }}</span></li>
                    @include('partials.logout')
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid py-4">
        @include('partials.flash')
        @yield('content')
    </main>
@endsection
