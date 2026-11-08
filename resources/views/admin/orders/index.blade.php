@extends('layouts.admin')

@section('title', 'Monitoring Pesanan')

@section('content')
    <h1 class="mb-3">Pesanan</h1>

    <form method="GET" class="row g-2 align-items-end mb-3">
        <x-field name="status" label="Status" type="select" :value="$filter['status'] ?? ''" class="col-6 col-md-3 col-xl-2 mb-0"
            :options="['' => 'Semua status'] + collect(\App\Enums\OrderStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()" />
        <x-field name="metode" label="Metode" type="select" :value="$filter['metode'] ?? ''" class="col-6 col-md-2 mb-0"
            :options="['' => 'Semua', 'ONLINE' => 'Online', 'KASIR' => 'Kasir']" />
        <x-field name="dari" label="Dari" type="date" :value="$filter['dari'] ?? ''" class="col-6 col-md-2 mb-0" />
        <x-field name="sampai" label="Sampai" type="date" :value="$filter['sampai'] ?? ''" class="col-6 col-md-2 mb-0" />
        <div class="col-auto">
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="perlu_refund" value="1" id="perlu_refund" @checked(request()->boolean('perlu_refund'))>
                <label class="form-check-label" for="perlu_refund">Perlu refund</label>
            </div>
        </div>
        <div class="col-auto"><button class="btn btn-primary" type="submit">Terapkan</button></div>
        @if (array_filter($filter))
            <div class="col-auto"><a href="{{ route('admin.orders.index') }}" class="btn btn-link">Hapus filter</a></div>
        @endif
    </form>

    @include('partials.orders-table', ['orders' => $orders, 'detailRoute' => 'admin.orders.show', 'refundIds' => $refundIds])
@endsection
