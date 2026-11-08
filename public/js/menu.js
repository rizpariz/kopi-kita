/* Modal kustomisasi katalog: muat opsi, hitung harga langsung, validasi min/max per grup, tambah ke keranjang lewat AJAX. */
(function ($) {
    'use strict';

    var $modal = $('#customize-modal');
    var $form = $('#customize-form');
    var $groups = $('#customize-groups');
    var $qty = $('#customize-qty');
    var $submit = $('#customize-submit');
    var modal = bootstrap.Modal.getOrCreateInstance($modal[0]);
    var product = null;
    var attempted = false;

    function hint(group) {
        var min = Math.max(group.min_pilih, group.wajib ? 1 : 0);
        if (group.max_pilih === 1) {
            return min ? 'Wajib, pilih 1' : 'Opsional, pilih 1';
        }
        return min ? 'Pilih ' + min + ' sampai ' + group.max_pilih : 'Opsional, maksimal ' + group.max_pilih;
    }

    function render(data) {
        product = data;
        $('#customize-title').text(data.nama);
        $('#customize-base').text('Harga dasar ' + rupiah(data.harga_dasar));
        $('#customize-product').val(data.id);
        $groups.empty().attr('aria-busy', 'false');

        $.each(data.groups, function (_, group) {
            var type = group.max_pilih === 1 ? 'radio' : 'checkbox';
            var hintId = 'group-hint-' + group.id;
            var errId = 'group-error-' + group.id;
            var $set = $('<fieldset class="kk-group"></fieldset>').attr({
                'data-min': Math.max(group.min_pilih, group.wajib ? 1 : 0),
                'data-max': group.max_pilih
            });
            $set.append($('<legend></legend>').text(group.nama));
            $set.append($('<p class="small text-body-secondary mb-2"></p>').attr('id', hintId).text(hint(group)));

            var $choices = $('<div class="d-flex flex-wrap gap-2"></div>');
            $.each(group.options, function (_, option) {
                var id = 'opt-' + option.id;
                var $input = $('<input class="btn-check">').attr({
                    type: type, id: id, value: option.id,
                    name: type === 'radio' ? 'group-' + group.id : 'group-' + group.id + '[]',
                    'data-delta': option.selisih_harga,
                    'aria-describedby': hintId + ' ' + errId
                }).prop('disabled', !option.tersedia);
                var $label = $('<label class="btn kk-choice px-3"></label>').attr('for', id).text(option.nama);
                if (option.selisih_harga) {
                    $label.append(' ', $('<span class="kk-delta"></span>').text('+' + rupiah(option.selisih_harga)));
                }
                if (!option.tersedia) {
                    $label.append(' ', $('<span class="kk-delta"></span>').text('(habis)'));
                }
                $choices.append($input, $label);
            });
            $set.append($choices, $('<div class="invalid-feedback"></div>').attr('id', errId));
            $groups.append($set);
        });

        $submit.prop('disabled', false);
        updateTotal();
    }

    function qty() {
        var n = parseInt($qty.val(), 10);
        return isNaN(n) ? 1 : Math.min(10, Math.max(1, n));
    }

    function updateTotal() {
        if (!product) {
            return;
        }
        var unit = product.harga_dasar;
        $groups.find('.btn-check:checked').each(function () {
            unit += Number($(this).data('delta')) || 0;
        });
        $('#customize-total').text(rupiah(unit * qty()));
    }

    // Validasi klien memakai setCustomValidity; server tetap memvalidasi ulang (BR-19).
    function validate() {
        var valid = true;
        $groups.find('.kk-group').each(function () {
            var $set = $(this);
            var chosen = $set.find('.btn-check:checked').length;
            var min = Number($set.data('min'));
            var max = Number($set.data('max'));
            var name = $set.find('legend').text();
            var message = '';
            if (chosen < min) {
                message = 'Pilih minimal ' + min + ' untuk ' + name + '.';
            } else if (chosen > max) {
                message = 'Pilih maksimal ' + max + ' untuk ' + name + '.';
            }
            $set.find('.btn-check').first()[0].setCustomValidity(message);
            $set.toggleClass('is-invalid', !!message).find('.invalid-feedback').text(message).toggleClass('d-block', !!message);
            valid = valid && !message;
        });

        var n = Number($qty.val());
        var qtyMessage = Number.isInteger(n) && n >= 1 && n <= 10 ? '' : 'Jumlah 1 sampai 10.';
        $qty[0].setCustomValidity(qtyMessage);
        $qty.toggleClass('is-invalid', !!qtyMessage);
        $('#customize-qty-error').text(qtyMessage).toggleClass('d-block', !!qtyMessage);

        return valid && !qtyMessage;
    }

    function reset() {
        product = null;
        attempted = false;
        $form[0].reset();
        $('#customize-title').text('Memuat menu');
        $('#customize-base, #customize-total').text('');
        $('#customize-error').addClass('d-none').text('');
        $('#customize-note').removeClass('is-invalid');
        $qty.removeClass('is-invalid');
        $submit.prop('disabled', true);
        $groups.attr('aria-busy', 'true').html('<div class="text-center py-4"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden">Memuat opsi</span></div></div>');
    }

    $(document).on('click', '.js-customize', function () {
        reset();
        modal.show();
        $.getJSON('/menu/' + $(this).data('product-id') + '/opsi')
            .done(function (res) { render(res.data); })
            .fail(function () { modal.hide(); });
    });

    // Grup radio opsional (min 0) dapat dikosongkan lagi dengan mengeklik pilihan yang sama.
    $groups.on('pointerdown', '.kk-choice', function () {
        var input = document.getElementById($(this).attr('for'));
        $(input).data('was-checked', input.checked);
    });
    $groups.on('click', '.btn-check[type=radio]', function () {
        var $set = $(this).closest('.kk-group');
        if ($(this).data('was-checked') && Number($set.data('min')) === 0) {
            this.checked = false;
            $(this).trigger('change');
        }
        $(this).data('was-checked', false);
    });

    $form.on('change input', function () {
        updateTotal();
        if (attempted) {
            validate();
        }
    });

    $form.on('click', '[data-step]', function () {
        $qty.val(Math.min(10, Math.max(1, qty() + Number($(this).data('step'))))).trigger('change');
    });

    $form.on('submit', function (e) {
        e.preventDefault();
        attempted = true;
        $('#customize-error').addClass('d-none');
        if (!validate()) {
            $form.find(':invalid').first().trigger('focus');
            return;
        }

        $submit.prop('disabled', true);
        $.ajax({
            url: $form.data('url'),
            method: 'POST',
            data: {
                product_id: product.id,
                qty: $qty.val(),
                catatan: $('#customize-note').val(),
                options: $groups.find('.btn-check:checked').map(function () { return this.value; }).get()
            }
        }).done(function (res) {
            modal.hide();
            $('.js-cart-count').text(res.data.cart_count);
            toast(res.message, 'success', { href: '/keranjang', label: 'Lihat keranjang' });
        }).fail(function (xhr) {
            if (xhr.status !== 422) {
                return;
            }
            var errors = (xhr.responseJSON && xhr.responseJSON.errors) || {};
            if (errors.catatan) {
                $('#customize-note').addClass('is-invalid');
                $('#customize-note-error').text(errors.catatan[0]);
            }
            if (errors.qty) {
                $qty.addClass('is-invalid');
                $('#customize-qty-error').text(errors.qty[0]).addClass('d-block');
            }
            var general = (errors.options || errors.product_id || [])[0];
            if (general) {
                $('#customize-error').removeClass('d-none').text(general);
            }
        }).always(function () {
            $submit.prop('disabled', false);
        });
    });
})(jQuery);
