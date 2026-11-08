@extends('layouts.admin')

@section('title', 'Opsi '.$product->nama)

@section('content')
    <a href="{{ route('admin.products.index') }}" class="small">Kembali ke produk</a>
    <h1 class="mb-1">Opsi {{ $product->nama }}</h1>
    <p class="text-body-secondary">Grup tanpa opsi tidak tampil di katalog. Grup wajib minimal memilih 1.</p>

    @foreach ($product->optionGroups as $group)
        <section class="card mb-3" aria-labelledby="group-{{ $group->id }}">
            <div class="card-body">
                <h2 class="h5" id="group-{{ $group->id }}">{{ $group->nama }}
                    <span class="small fw-normal text-body-secondary">{{ $group->wajib ? 'wajib' : 'opsional' }}, pilih {{ $group->min_pilih }} sampai {{ $group->max_pilih }}</span>
                </h2>
                <details class="mb-3">
                    <summary class="small">Ubah grup</summary>
                    <form method="POST" action="{{ route('admin.option-groups.update', $group) }}" class="row g-2 align-items-end mt-1" novalidate>
                        @csrf
                        @method('PUT')
                        @include('admin.products.group-fields', ['group' => $group, 'bag' => "group{$group->id}"])
                        <div class="col-auto mb-3 d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">Simpan grup</button>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-delete-url="{{ route('admin.option-groups.destroy', $group) }}" data-delete-name="grup {{ $group->nama }}">Hapus grup</button>
                        </div>
                    </form>
                </details>

                <div class="row g-2 small fw-semibold text-body-secondary px-1 d-none d-sm-flex" aria-hidden="true">
                    <div class="col-sm-4">Nama opsi</div><div class="col-sm-3">Selisih harga (Rp)</div><div class="col-sm-2">Status</div>
                </div>
                <ul class="list-unstyled mb-3">
                    @foreach ($group->options as $option)
                        @php($bag = "option{$option->id}")
                        <li class="border-bottom py-2">
                            <form method="POST" action="{{ route('admin.options.update', $option) }}" class="row g-2 align-items-center px-1" novalidate>
                                @csrf
                                @method('PUT')
                                <x-field name="nama" label="Nama opsi" :bag="$bag" :value="$option->nama" class="col-sm-4 mb-0 kk-inline-field" required maxlength="50" />
                                <x-field name="selisih_harga" label="Selisih harga" type="number" :bag="$bag" :value="(int) $option->selisih_harga" class="col-sm-3 mb-0 kk-inline-field" min="0" step="500" />
                                <x-field name="tersedia" label="Tersedia" type="switch" :bag="$bag" :value="$option->tersedia" class="col-sm-2 mb-0" />
                                <div class="col-sm-3 text-sm-end">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Simpan</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-delete-url="{{ route('admin.options.destroy', $option) }}" data-delete-name="opsi {{ $option->nama }}">Hapus</button>
                                </div>
                            </form>
                        </li>
                    @endforeach
                </ul>
                @php($bag = "newOption{$group->id}")
                <form method="POST" action="{{ route('admin.options.store', $group) }}" class="row g-2 align-items-end" novalidate>
                    @csrf
                    <x-field name="nama" label="Opsi baru" :bag="$bag" class="col-sm-4 mb-0" maxlength="50" placeholder="Misalnya Oat Milk" />
                    <x-field name="selisih_harga" label="Selisih harga" type="number" :bag="$bag" :value="0" class="col-sm-3 mb-0" min="0" step="500" />
                    <input type="hidden" name="tersedia" value="1">
                    <div class="col-auto"><button type="submit" class="btn btn-sm btn-primary">Tambah opsi</button></div>
                </form>
            </div>
        </section>
    @endforeach

    <section class="card" aria-labelledby="new-group">
        <div class="card-body">
            <h2 class="h5" id="new-group">Tambah grup opsi</h2>
            <form method="POST" action="{{ route('admin.option-groups.store', $product) }}" class="row g-2 align-items-end" novalidate>
                @csrf
                @include('admin.products.group-fields', ['group' => new \App\Models\OptionGroup(['min_pilih' => 0, 'max_pilih' => 1, 'urutan' => $product->optionGroups->count() + 1]), 'bag' => 'newGroup'])
                <div class="col-auto mb-3"><button type="submit" class="btn btn-primary">Tambah grup</button></div>
            </form>
        </div>
    </section>
@endsection
