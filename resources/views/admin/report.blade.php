@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
    <h1 class="mb-3">Laporan penjualan</h1>
    <form method="GET" class="row g-2 align-items-end mb-4">
        <x-field name="dari" label="Dari" type="date" :value="$dari->toDateString()" class="col-6 col-md-3 col-xl-2 mb-0" />
        <x-field name="sampai" label="Sampai" type="date" :value="$sampai->toDateString()" class="col-6 col-md-3 col-xl-2 mb-0" />
        <div class="col-auto"><button class="btn btn-primary" type="submit">Tampilkan</button></div>
    </form>

    <p class="text-body-secondary">Pesanan berstatus Selesai yang diserahkan {{ $dari->format('d/m/Y') }} sampai {{ $sampai->format('d/m/Y') }}.</p>
    <div class="row g-3 mb-4">
        <div class="col-sm-4"><div class="card kk-stat h-100"><div class="card-body">
            <p class="mb-1 text-body-secondary">Pesanan selesai</p><p class="fs-2 fw-bold mb-0">{{ $count }}</p>
        </div></div></div>
        <div class="col-sm-4"><div class="card kk-stat h-100"><div class="card-body">
            <p class="mb-1 text-body-secondary">Pendapatan</p><p class="kk-price fs-2 mb-0">@rp($revenue)</p>
        </div></div></div>
        <div class="col-sm-4"><div class="card kk-stat h-100"><div class="card-body">
            <p class="mb-1 text-body-secondary">Total diskon voucher</p><p class="fs-2 fw-bold mb-0">@rp($discount)</p>
        </div></div></div>
    </div>

    <h2 class="h5">10 menu terlaris</h2>
    @if ($topProducts->isEmpty())
        <p class="text-body-secondary">Belum ada penjualan di rentang ini.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle kk-form">
                <thead><tr><th scope="col">Peringkat</th><th scope="col">Menu</th><th scope="col" class="text-end">Terjual</th><th scope="col" class="text-end">Penjualan</th></tr></thead>
                <tbody>
                    @foreach ($topProducts as $i => $row)
                        <tr><td>{{ $i + 1 }}</td><td>{{ $row->nama_snapshot }}</td><td class="text-end">{{ (int) $row->jumlah }}</td><td class="text-end text-nowrap">@rp($row->pendapatan)</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
