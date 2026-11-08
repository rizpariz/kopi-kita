@extends('layouts.staff')

@section('title', 'Pesanan '.$order->kode_pickup)

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <a href="{{ route('staff.history') }}" class="small">Kembali ke riwayat</a>
            <h1 class="mb-1"><span class="font-monospace">{{ $order->kode_pickup }}</span></h1>
            <p class="mb-0">{{ $order->user->nama }}, {{ $order->created_at->format('d/m/Y H:i') }} <x-status-badge :status="$order->status" class="ms-1" /></p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if ($order->hasReceipt())
                <a href="{{ route('staff.orders.receipt', $order) }}" class="btn btn-outline-primary" target="_blank" rel="noopener">Cetak Struk</a>
            @endif
            @if ($order->status->canTransitionTo(\App\Enums\OrderStatus::Dibatalkan))
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancel-modal">Batalkan Pesanan</button>
            @endif
        </div>
    </div>

    @include('partials.order-detail')

    @if ($order->status->canTransitionTo(\App\Enums\OrderStatus::Dibatalkan))
        <div class="modal fade" id="cancel-modal" tabindex="-1" aria-labelledby="cancel-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" id="cancel-form" data-url="{{ route('staff.orders.cancel', $order) }}" novalidate>
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="cancel-title">Batalkan pesanan {{ $order->kode_pickup }}</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <label for="alasan" class="form-label">Alasan (tampil ke pelanggan)</label>
                        <textarea id="alasan" name="alasan" class="form-control" rows="3" maxlength="255" required aria-describedby="alasan-error"></textarea>
                        <div class="invalid-feedback" id="alasan-error"></div>
                        @if ($order->paid_at)
                            <p class="small text-danger mt-2 mb-0">Pesanan sudah dibayar. Setelah dibatalkan, pesanan masuk daftar Perlu Refund di panel admin.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-danger">Batalkan Pesanan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $('#cancel-form').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var $alasan = $('#alasan').removeClass('is-invalid');
            var $button = $form.find('[type=submit]').prop('disabled', true);
            $.ajax({ url: $form.data('url'), method: 'POST', data: { alasan: $alasan.val() } })
                .done(function () { window.location.reload(); })
                .fail(function (xhr) {
                    if (xhr.status === 422) {
                        $alasan.addClass('is-invalid');
                        $('#alasan-error').text(xhr.responseJSON.errors.alasan[0]);
                    }
                    $button.prop('disabled', false);
                });
        });
    </script>
@endpush
