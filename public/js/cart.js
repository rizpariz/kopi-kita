/* Halaman keranjang: ubah jumlah dan hapus item tanpa memuat ulang halaman. */
(function ($) {
    'use strict';

    function applySummary(cart) {
        $('.js-cart-count, #cart-summary-count').text(cart.count);
        $('#cart-summary-subtotal').text(rupiah(cart.subtotal));
        $('#cart-live').text('Subtotal ' + rupiah(cart.subtotal));
        if (cart.count === 0) {
            $('#cart-content').remove();
            $('#cart-empty').removeClass('d-none');
        }
    }

    $(document).on('change', '.js-qty', function () {
        var $input = $(this);
        var $item = $input.closest('[data-url]');
        var n = Number($input.val());
        if (!Number.isInteger(n) || n < 1 || n > 10) {
            $input.addClass('is-invalid');
            toast('Jumlah 1 sampai 10.', 'warning');
            return;
        }
        $input.removeClass('is-invalid').prop('disabled', true);
        $.ajax({ url: $item.data('url'), method: 'PATCH', data: { qty: n } })
            .done(function (res) {
                $('#subtotal-' + res.data.item.id).text(rupiah(res.data.item.subtotal));
                applySummary(res.data.cart);
            })
            .fail(function (xhr) {
                if (xhr.status === 422) {
                    $input.addClass('is-invalid');
                    toast(xhr.responseJSON.message, 'warning');
                }
            })
            .always(function () { $input.prop('disabled', false); });
    });

    // Stepper kurang dan tambah: ubah angka lalu kirim seperti mengetik manual.
    $(document).on('click', '.js-step', function () {
        var $input = $(this).closest('.input-group').find('.js-qty');
        var next = Math.min(10, Math.max(1, (parseInt($input.val(), 10) || 1) + Number($(this).data('step'))));
        if (next !== Number($input.val())) {
            $input.val(next).trigger('change');
        }
    });

    $(document).on('click', '.js-remove', function () {
        var $button = $(this).prop('disabled', true);
        var $item = $button.closest('[data-url]');
        $.ajax({ url: $item.data('url'), method: 'DELETE' })
            .done(function (res) {
                $item.remove();
                applySummary(res.data.cart);
                toast(res.message);
            })
            .fail(function () { $button.prop('disabled', false); });
    });
})(jQuery);
