@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1 class="mb-1">Hari ini</h1>
    <p class="text-body-secondary">{{ now()->translatedFormat('l, d F Y') }}</p>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card kk-stat h-100"><div class="card-body">
                <p class="mb-1 text-body-secondary">Pendapatan</p>
                <p class="kk-price fs-2 mb-0">@rp($revenue)</p>
                <p class="small text-body-secondary mb-0">Pesanan dibayar hari ini, tanpa yang dibatalkan.</p>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('admin.orders.index', ['perlu_refund' => 1]) }}" class="card kk-stat h-100 text-decoration-none @if ($refundCount) border-danger @endif">
                <div class="card-body">
                    <p class="mb-1 text-body-secondary">Perlu refund</p>
                    <p class="fs-2 fw-bold mb-0 @if ($refundCount) text-danger @endif">{{ $refundCount }}</p>
                    <p class="small text-body-secondary mb-0">Lunas tetapi dibatalkan atau kedaluwarsa.</p>
                </div>
            </a>
        </div>
        <div class="col-xl-6">
            <div class="card h-100"><div class="card-body">
                <p class="mb-2 text-body-secondary">Pesanan dibuat hari ini per status</p>
                <ul class="list-unstyled d-flex flex-wrap gap-2 mb-0">
                    @foreach (\App\Enums\OrderStatus::cases() as $status)
                        <li>
                            <a href="{{ route('admin.orders.index', ['status' => $status->value, 'dari' => today()->toDateString()]) }}" class="text-decoration-none">
                                <x-status-badge :status="$status" class="fs-6" /> <span class="fw-semibold text-body">{{ $counts[$status->value] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div></div>
        </div>
    </div>

    <h2 class="h5">Menunggu refund</h2>
    @if ($refunds->isEmpty())
        <p class="text-body-secondary">Tidak ada pesanan yang menunggu refund.</p>
    @else
        <ul class="list-group">
            @foreach ($refunds as $order)
                <li class="list-group-item d-flex justify-content-between align-items-center gap-2">
                    <span><a href="{{ route('admin.orders.show', $order) }}" class="font-monospace fw-semibold">{{ $order->kode_pickup }}</a> {{ $order->user->nama }}</span>
                    <span class="text-nowrap">@rp($order->total)</span>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
