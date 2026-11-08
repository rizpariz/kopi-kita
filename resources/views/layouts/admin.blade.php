{{-- Layout panel Super Admin. --}}
@extends('layouts.base')

@section('body')
    {{-- navbar-expand-xxl: menu admin banyak, jadi baru tampil berjajar di layar sangat lebar (>= 1400px). --}}
    <nav class="navbar navbar-expand-xxl navbar-dark kk-navbar">
        <div class="container-fluid">
            <a class="navbar-brand fw-semibold" href="{{ url('/admin') }}">Kopi &amp; Kita <span class="badge text-bg-light ms-1">Admin</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-admin" aria-controls="nav-admin" aria-expanded="false" aria-label="Buka menu navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav-admin">
                <ul class="navbar-nav me-auto">
                    @include('partials.nav-admin')
                </ul>
                <ul class="navbar-nav align-items-xxl-center">
                    <li class="nav-item"><span class="navbar-text me-2">{{ auth()->user()->nama }}</span></li>
                    @include('partials.logout')
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid py-4">
        @include('partials.flash')
        @foreach (['hapus', 'refund', 'aktif'] as $key)
            @error($key)
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
            @enderror
        @endforeach
        @yield('content')
    </main>

    {{-- Satu modal konfirmasi hapus untuk semua daftar: tombol [data-delete-url] mengisi action dan nama. --}}
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="delete-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" id="delete-form">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h2 class="modal-title h5" id="delete-title">Hapus <span id="delete-name"></span>?</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">Data yang dihapus tidak dapat dikembalikan.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, hapus</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '[data-delete-url]', function () {
            $('#delete-form').attr('action', $(this).data('delete-url'));
            $('#delete-name').text($(this).data('delete-name'));
            bootstrap.Modal.getOrCreateInstance(document.getElementById('delete-modal')).show();
        });
    </script>
@endpush
