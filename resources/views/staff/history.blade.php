@extends('layouts.staff')

@section('title', 'Riwayat Pesanan')

@section('content')
    <h1 class="mb-3">Riwayat pesanan</h1>

    <form method="GET" class="row g-2 align-items-end mb-3" role="search">
        <x-field name="q" label="Kode atau nama pelanggan" :value="$filter['q'] ?? ''" class="col-sm-6 col-lg-4 mb-0" maxlength="100" />
        <x-field name="dari" label="Dari" type="date" :value="$filter['dari'] ?? ''" class="col-6 col-sm-3 col-lg-2 mb-0" />
        <x-field name="sampai" label="Sampai" type="date" :value="$filter['sampai'] ?? ''" class="col-6 col-sm-3 col-lg-2 mb-0" />
        <div class="col-auto"><button class="btn btn-primary" type="submit">Terapkan</button></div>
        @if (array_filter($filter))
            <div class="col-auto"><a href="{{ route('staff.history') }}" class="btn btn-link">Hapus filter</a></div>
        @endif
    </form>

    @include('partials.orders-table', ['orders' => $orders, 'detailRoute' => 'staff.history.show'])
@endsection
