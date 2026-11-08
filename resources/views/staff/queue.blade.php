@extends('layouts.staff')

@section('title', 'Antrean')

@section('content')
    <div id="queue" data-url="{{ route('staff.queue.data') }}" data-status-url="{{ url('/karyawan/pesanan/__KODE__/status') }}">
        <div class="d-flex flex-wrap justify-content-between align-items-baseline gap-2 mb-3">
            <h1 class="mb-0">Antrean</h1>
            <p class="small text-body-secondary mb-0" role="status" aria-live="polite" id="queue-updated">Memuat antrean...</p>
        </div>
        <div class="row g-4">
            <section class="col-lg-8" aria-labelledby="col-antrean">
                <h2 class="h5 kk-col-title" id="col-antrean">Dibuat <span class="badge text-bg-light border" id="count-antrean">0</span></h2>
                <p class="small text-body-secondary">Urut waktu bayar. Proses kartu paling atas lebih dulu.</p>
                <div class="row row-cols-1 row-cols-md-2 g-3" id="list-antrean"></div>
                <p class="text-body-secondary d-none" id="empty-antrean">Belum ada pesanan yang perlu dibuat.</p>
            </section>
            <section class="col-lg-4" aria-labelledby="col-siap">
                <h2 class="h5 kk-col-title" id="col-siap">Siap diambil <span class="badge text-bg-light border" id="count-siap">0</span></h2>
                <p class="small text-body-secondary">Panggil kode, lalu serahkan di halaman <a href="{{ route('staff.pickup') }}">Pengambilan</a>.</p>
                <div class="d-flex flex-column gap-3" id="list-siap"></div>
                <p class="text-body-secondary d-none" id="empty-siap">Belum ada pesanan siap diambil.</p>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/queue.js') }}"></script>
@endpush
