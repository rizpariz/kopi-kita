/* Checkout: pratinjau voucher lewat AJAX dan cegah kirim ganda. */
(function ($) {
    'use strict';

    var $kode = $('#voucher-kode');
    var $button = $('#voucher-apply');

    function clearVoucher() {
        $('#voucher-input').val('');
        $('#voucher-ok').addClass('d-none').text('');
        $('#sum-diskon').text('Rp0');
        $('#sum-total').text($('#sum-subtotal').text());
    }

    $button.on('click', function () {
        var kode = $.trim($kode.val()).toUpperCase();
        $kode.removeClass('is-invalid');
        if (!kode) {
            clearVoucher();
            return;
        }
        $button.prop('disabled', true);
        $.ajax({ url: $button.data('url'), method: 'POST', data: { kode: kode } })
            .done(function (res) {
                $('#voucher-input').val(res.data.kode);
                $('#sum-diskon').text('-' + rupiah(res.data.diskon));
                $('#sum-total').text(rupiah(res.data.total));
                $('#voucher-ok').removeClass('d-none').text('Voucher ' + res.data.kode + ' dipakai, hemat ' + rupiah(res.data.diskon) + '.');
            })
            .fail(function (xhr) {
                clearVoucher();
                if (xhr.status === 422) {
                    $kode.addClass('is-invalid');
                    $('#voucher-feedback').text(xhr.responseJSON.message);
                }
            })
            .always(function () { $button.prop('disabled', false); });
    });

    $kode.on('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $button.trigger('click');
        }
    }).on('input', clearVoucher);

    // Teks tombol menyebut langkah berikutnya sesuai metode bayar.
    function submitLabel() {
        $('#checkout-submit').text($('input[name=metode]:checked').val() === 'ONLINE'
            ? 'Buat Pesanan dan Bayar Online'
            : 'Buat Pesanan, Bayar di Kasir');
    }
    $('input[name=metode]').on('change', submitLabel);
    submitLabel();

    $('#checkout-form').on('submit', function () {
        // Kode yang diketik tetapi belum dipratinjau tetap dikirim; server memvalidasinya dan menampilkan galat di field.
        if (!$('#voucher-input').val() && $.trim($kode.val())) {
            $('#voucher-input').val($.trim($kode.val()).toUpperCase());
        }
        $('#checkout-submit').prop('disabled', true).text('Membuat pesanan...');
    });
})(jQuery);
