{{-- Layout halaman pelanggan dan halaman publik. --}}
@extends('layouts.base')

@section('body')
    <nav class="navbar navbar-expand-md navbar-dark kk-navbar">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ url('/') }}">Kopi &amp; Kita</a>
            @auth
                @if (auth()->user()->hasRole(\App\Enums\Role::Pelanggan))
                    {{-- Ponsel: keranjang selalu terlihat di samping tombol menu. --}}
                    <a href="{{ route('cart.index') }}" class="btn kk-cart-mobile d-md-none ms-auto me-2">
                        Keranjang <span class="badge text-bg-light js-cart-count">{{ $cartCount ?? 0 }}</span>
                    </a>
                @endif
            @endauth
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-app" aria-controls="nav-app" aria-expanded="false" aria-label="Buka menu navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav-app">
                <ul class="navbar-nav ms-auto">
                    @include('partials.nav-app')
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @include('partials.flash')
        @yield('content')
    </main>

    <footer class="border-top py-4 small text-body-secondary">
        <div class="container d-flex flex-column flex-md-row justify-content-between gap-2">
            @if ($kkStore)
                <div>
                    <span class="fw-semibold text-body">{{ $kkStore->nama_kedai }}</span><br>
                    {{ $kkStore->alamat }}<br>
                    Buka {{ substr($kkStore->jam_buka, 0, 5) }} sampai {{ substr($kkStore->jam_tutup, 0, 5) }}, kontak {{ $kkStore->kontak }}
                </div>
            @endif
            <div class="text-md-end">Tugas besar Pemrograman Web<br>Kelompok 2 S1IT-KJ-24-004</div>
        </div>
    </footer>
@endsection
