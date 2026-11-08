@extends('layouts.print')

@section('title', 'Struk '.$order->kode_pickup)

@section('content')
    <div class="center">
        <strong>{{ $setting->nama_kedai }}</strong><br>
        {{ $setting->alamat }}<br>
        {{ $setting->kontak }}
    </div>
    <hr>
    <div>Kode : <strong>{{ $order->kode_pickup }}</strong></div>
    <div>Waktu: {{ ($order->paid_at ?? $order->created_at)->format('d/m/Y H:i') }}</div>
    <div>Nama : {{ $order->user->nama }}</div>
    <hr>
    @foreach ($order->items as $item)
        <div class="row-line"><span>{{ $item->qty }}x {{ $item->nama_snapshot }}</span><span>{{ number_format($item->subtotal, 0, ',', '.') }}</span></div>
        @foreach ($item->options as $option)
            <div class="sub">- {{ $option->nama_snapshot }}</div>
        @endforeach
        @if ($item->catatan)
            <div class="sub">* {{ $item->catatan }}</div>
        @endif
    @endforeach
    <hr>
    <div class="row-line"><span>Subtotal</span><span>{{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
    <div class="row-line"><span>Diskon</span><span>-{{ number_format($order->diskon, 0, ',', '.') }}</span></div>
    <div class="row-line"><strong>TOTAL</strong><strong>Rp{{ number_format($order->total, 0, ',', '.') }}</strong></div>
    <hr>
    <div>Bayar: {{ $payment?->metode === \App\Enums\PaymentMethod::Kasir ? 'Bayar di Kasir' : 'Online' }}{{ $payment?->payment_type ? ' ('.$payment->payment_type.')' : '' }}</div>
    <div>Status: {{ $order->status->label() }}</div>
    <hr>
    <div class="center">Terima kasih!</div>
@endsection
