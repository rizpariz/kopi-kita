@extends('layouts.app')

@section('title', 'Daftar')

{{-- kk-auth: gaya khusus form login/daftar. Lihat bagian "9. Login dan daftar" di public/css/app.css. --}}
@section('body_class', 'kk-auth')

@section('content')
    <div class="kk-auth-wrap">
        <div class="kk-auth-card kk-auth-card-wide">
            {{-- Panel kiri: ajakan dan keuntungan punya akun (hiasan) --}}
            <aside class="kk-auth-side" aria-hidden="true">
                <span class="kk-mono">(Daftar) &mdash; Baru di sini?</span>
                <p class="kk-auth-side-title">Yuk gabung. <span class="kk-italic">ngopi jadi gampang.</span></p>

                <ul class="kk-perks">
                    @foreach ([
                        'Pesan langsung dari HP',
                        'Atur ukuran, es, dan gula sesukamu',
                        'Pantau status pesanan',
                        'Riwayat dan pesan ulang sekali klik',
                    ] as $perk)
                        <li><span>({{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }})</span> {{ $perk }}</li>
                    @endforeach
                </ul>

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
                        <span class="kk-cover-caption"><span>Gbr. 03</span><span>Gratis daftar</span></span>
                    </div>
                </div>
            </aside>

            {{-- Panel kanan: form daftar --}}
            <div class="kk-auth-main">
                <h1 class="kk-auth-title">Daftar akun pelanggan</h1>
                <p class="kk-auth-sub mb-4">Isi data di bawah ini. Cuma butuh satu menit.</p>

                <form method="POST" action="{{ url('/register') }}" novalidate>
                    @csrf
                    @foreach ([
                        ['nama', 'Nama lengkap', 'text', 'name', 'Contoh: Budi Santoso'],
                        ['email', 'Email', 'email', 'email', 'nama@email.com'],
                        ['no_hp', 'Nomor HP', 'tel', 'tel', '08xxxxxxxxxx'],
                    ] as [$field, $label, $type, $autocomplete, $placeholder])
                        <div class="mb-3">
                            <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                            <input type="{{ $type }}" id="{{ $field }}" name="{{ $field }}" value="{{ old($field) }}" required autocomplete="{{ $autocomplete }}"
                                placeholder="{{ $placeholder }}"
                                class="form-control kk-input @error($field) is-invalid @enderror" @error($field) aria-describedby="{{ $field }}-error" @enderror>
                            @error($field)
                                <div class="invalid-feedback" id="{{ $field }}-error">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" required minlength="8" maxlength="64" autocomplete="new-password"
                                class="form-control kk-input @error('password') is-invalid @enderror" aria-describedby="password-help @error('password') password-error @enderror">
                        </div>
                        <div class="col-sm-6">
                            <label for="password_confirmation" class="form-label">Ulangi password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" class="form-control kk-input">
                        </div>
                        <div class="col-12 d-flex flex-wrap align-items-center justify-content-between gap-2 mt-2">
                            <div class="form-text mt-0" id="password-help">8 sampai 64 karakter.</div>
                            <button type="button" class="kk-password-toggle kk-password-toggle-inline js-password-toggle" aria-controls="password password_confirmation" aria-pressed="false">Lihat</button>
                        </div>
                        @error('password')
                            <div class="col-12 mt-1">
                                <div class="invalid-feedback d-block" id="password-error">{{ $message }}</div>
                            </div>
                        @enderror
                    </div>

                    <div class="form-check kk-consent mb-4">
                        <input type="checkbox" id="setuju_privasi" name="setuju_privasi" value="1" @checked(old('setuju_privasi'))
                            class="form-check-input @error('setuju_privasi') is-invalid @enderror" @error('setuju_privasi') aria-describedby="setuju_privasi-error" @enderror>
                        <label for="setuju_privasi" class="form-check-label">
                            Saya menyetujui <a href="{{ route('privacy') }}" target="_blank" rel="noopener">Kebijakan Privasi</a> Kopi &amp; Kita.
                        </label>
                        @error('setuju_privasi')
                            <div class="invalid-feedback" id="setuju_privasi-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">Daftar</button>
                </form>

                <p class="kk-auth-alt mt-4 mb-0 text-center">
                    Sudah punya akun? <a href="{{ route('login') }}" class="kk-link-arrow">Masuk</a>
                </p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Tombol Lihat/Sembunyikan untuk kedua kolom password sekaligus.
        document.querySelectorAll('.js-password-toggle').forEach(function (button) {
            button.addEventListener('click', function () {
                var inputs = button.getAttribute('aria-controls').split(' ').map(function (id) {
                    return document.getElementById(id);
                });
                var show = inputs[0].type === 'password';
                inputs.forEach(function (input) {
                    input.type = show ? 'text' : 'password';
                });
                button.textContent = show ? 'Sembunyikan' : 'Lihat';
                button.setAttribute('aria-pressed', show ? 'true' : 'false');
            });
        });
    </script>
@endpush
