@extends('layouts.app')

@section('title', 'Masuk')

{{-- kk-auth: gaya khusus form login/daftar. Lihat bagian "9. Login dan daftar" di public/css/app.css. --}}
@section('body_class', 'kk-auth')

@section('content')
    <div class="kk-auth-wrap">
        <div class="kk-auth-card">
            {{-- Panel kiri: sapaan dan ilustrasi (hiasan) --}}
            <aside class="kk-auth-side" aria-hidden="true">
                <span class="kk-mono">(Masuk) &mdash; Welcome back</span>
                <p class="kk-auth-side-title">Halo lagi. <span class="kk-italic">kopimu udah nungguin.</span></p>

                <div class="kk-auth-art">
                    <div class="kk-cover">
                        <div class="kk-glass">
                            <span class="kk-glass-straw"></span>
                            <span class="kk-glass-cup">
                                <span class="kk-ice kk-ice-1"></span>
                                <span class="kk-ice kk-ice-2"></span>
                                <span class="kk-ice kk-ice-3"></span>
                            </span>
                        </div>
                        <span class="kk-cover-caption"><span>Gbr. 02</span><span>Pesan dulu, ambil tanpa antre</span></span>
                    </div>
                </div>
            </aside>

            {{-- Panel kanan: form masuk --}}
            <div class="kk-auth-main">
                <h1 class="kk-auth-title">Masuk</h1>
                <p class="kk-auth-sub mb-4">Masuk untuk memesan dan melihat status pesananmu.</p>

                <form method="POST" action="{{ url('/login') }}" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            placeholder="nama@email.com"
                            class="form-control kk-input @error('email') is-invalid @enderror" @error('email') aria-describedby="email-error" @enderror>
                        @error('email')
                            <div class="invalid-feedback" id="email-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="kk-password">
                            <input type="password" id="password" name="password" required autocomplete="current-password"
                                class="form-control kk-input @error('password') is-invalid @enderror" @error('password') aria-describedby="password-error" @enderror>
                            <button type="button" class="kk-password-toggle js-password-toggle" aria-controls="password" aria-pressed="false">Lihat</button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block" id="password-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100">Masuk</button>
                </form>

                <p class="kk-auth-alt mt-4 mb-0 text-center">
                    Belum punya akun? <a href="{{ route('register') }}" class="kk-link-arrow">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Tombol Lihat/Sembunyikan password.
        document.querySelectorAll('.js-password-toggle').forEach(function (button) {
            button.addEventListener('click', function () {
                var input = document.getElementById(button.getAttribute('aria-controls'));
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                button.textContent = show ? 'Sembunyikan' : 'Lihat';
                button.setAttribute('aria-pressed', show ? 'true' : 'false');
            });
        });
    </script>
@endpush
