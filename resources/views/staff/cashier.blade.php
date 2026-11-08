@extends('layouts.staff')

@section('title', 'Kasir')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <h1 class="mb-1">Konfirmasi pembayaran</h1>
            <p class="text-body-secondary">Masukkan kode pesanan pelanggan, cek dana masuk di aplikasi merchant, lalu konfirmasi.</p>

            <form id="find-form" data-url="{{ route('staff.orders.find') }}" data-mode="cashier"
                data-action-url="{{ url('/karyawan/pesanan/__KODE__/konfirmasi-bayar') }}" class="mb-4" novalidate>
                <label for="kode" class="form-label fw-semibold">Kode pesanan</label>
                <div class="input-group input-group-lg has-validation">
                    <input type="text" id="kode" name="kode" class="form-control font-monospace text-uppercase kk-code-input" maxlength="6"
                        autocomplete="off" autocapitalize="characters" spellcheck="false" required aria-describedby="kode-error" autofocus>
                    <button class="btn btn-primary" type="submit">Cari</button>
                    <div class="invalid-feedback" id="kode-error"></div>
                </div>
            </form>

            <div id="result" aria-live="polite"></div>
        </div>
    </div>

    <div class="modal fade" id="confirm-modal" tabindex="-1" aria-labelledby="confirm-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="confirm-title">Konfirmasi pembayaran</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-3">
                        <dt class="col-4 fw-normal">Kode</dt><dd class="col-8 font-monospace fw-bold" id="confirm-kode"></dd>
                        <dt class="col-4 fw-normal">Nama</dt><dd class="col-8" id="confirm-nama"></dd>
                        <dt class="col-4 fw-normal">Total</dt><dd class="col-8 kk-price" id="confirm-total"></dd>
                    </dl>
                    <p class="mb-0 fw-semibold" id="confirm-question"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="confirm-yes">Ya, Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/lookup.js') }}"></script>
@endpush
