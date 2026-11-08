@extends('layouts.staff')

@section('title', 'Ketersediaan')

@section('content')
    <h1 class="mb-1">Ketersediaan menu</h1>
    <p class="text-body-secondary">Matikan menu atau opsi yang habis. Katalog pelanggan langsung memakai status baru.</p>

    @foreach ($categories as $category)
        <section class="mb-4" aria-labelledby="cat-{{ $category->id }}">
            <h2 class="h5" id="cat-{{ $category->id }}">{{ $category->nama }}</h2>
            <ul class="list-group">
                @foreach ($category->products as $product)
                    <li class="list-group-item">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input js-availability" type="checkbox" role="switch" id="product-{{ $product->id }}"
                                    data-url="{{ route('staff.products.availability', $product) }}" @checked($product->tersedia)>
                                <label class="form-check-label fw-semibold" for="product-{{ $product->id }}">{{ $product->nama }}</label>
                            </div>
                            <span class="small js-availability-label" data-for="product-{{ $product->id }}">{{ $product->tersedia ? 'Tersedia' : 'Habis' }}</span>
                        </div>
                        @if ($product->activeOptionGroups->isNotEmpty())
                            <details class="mt-2">
                                <summary class="small">Opsi {{ $product->nama }} ({{ $product->activeOptionGroups->sum(fn ($g) => $g->options->where('tersedia', false)->count()) }} habis)</summary>
                                <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-1 mt-1">
                                    @foreach ($product->activeOptionGroups as $group)
                                        @foreach ($group->options as $option)
                                            <div class="col">
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input js-availability" type="checkbox" role="switch" id="option-{{ $option->id }}"
                                                        data-url="{{ route('staff.options.availability', $option) }}" @checked($option->tersedia)>
                                                    <label class="form-check-label small" for="option-{{ $option->id }}">{{ $group->nama }}: {{ $option->nama }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </details>
                        @endif
                    </li>
                @endforeach
            </ul>
        </section>
    @endforeach
@endsection

@push('scripts')
    <script>
        // Toggle AJAX; bila gagal, posisi switch dikembalikan.
        $(document).on('change', '.js-availability', function () {
            var input = this;
            var value = input.checked;
            input.disabled = true;
            $.ajax({ url: $(input).data('url'), method: 'PATCH', data: { tersedia: value ? 1 : 0 } })
                .done(function (res) {
                    $('.js-availability-label[data-for="' + input.id + '"]').text(res.data.label);
                    toast($('label[for="' + input.id + '"]').text() + ': ' + res.data.label);
                })
                .fail(function () { input.checked = !value; })
                .always(function () { input.disabled = false; });
        });
    </script>
@endpush
