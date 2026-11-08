{{-- Modal kustomisasi; isi grup opsi dirender public/js/menu.js dari GET /menu/{product}/opsi. --}}
<div class="modal fade" id="customize-modal" tabindex="-1" aria-labelledby="customize-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down">
        <form class="modal-content" id="customize-form" novalidate data-url="{{ route('cart.items.store') }}">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title h4 mb-0" id="customize-title">Memuat menu</h2>
                    <p class="small text-body-secondary mb-0" id="customize-base"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="product_id" id="customize-product">
                <div class="alert alert-danger d-none" id="customize-error" role="alert"></div>
                <div id="customize-groups" aria-busy="true">
                    <div class="text-center py-4"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden">Memuat opsi</span></div></div>
                </div>
                <div class="mb-1">
                    <label for="customize-note" class="form-label fw-semibold mb-1">Catatan <span class="fw-normal text-body-secondary">(opsional)</span></label>
                    <textarea class="form-control" id="customize-note" name="catatan" rows="2" maxlength="100" placeholder="Misalnya es dipisah" aria-describedby="customize-note-help customize-note-error"></textarea>
                    <div class="form-text" id="customize-note-help">Maksimal 100 karakter.</div>
                    <div class="invalid-feedback" id="customize-note-error"></div>
                </div>
            </div>
            <div class="modal-footer justify-content-between flex-nowrap gap-2">
                <div class="input-group input-group-sm w-auto flex-nowrap" role="group" aria-label="Jumlah">
                    <button type="button" class="btn btn-outline-secondary" data-step="-1" aria-label="Kurangi jumlah">&minus;</button>
                    <label for="customize-qty" class="visually-hidden">Jumlah</label>
                    <input type="number" class="form-control kk-qty" id="customize-qty" name="qty" value="1" min="1" max="10" required inputmode="numeric" aria-describedby="customize-qty-error">
                    <button type="button" class="btn btn-outline-secondary" data-step="1" aria-label="Tambah jumlah">+</button>
                </div>
                <div class="invalid-feedback position-absolute" id="customize-qty-error"></div>
                <button type="submit" class="btn btn-primary flex-grow-1" id="customize-submit" disabled>
                    Tambah ke keranjang <span class="ms-1 fw-semibold" id="customize-total"></span>
                </button>
            </div>
        </form>
    </div>
</div>
