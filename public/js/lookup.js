/* Kasir dan Pengambilan: cari pesanan dengan kode, tampilkan kartu hasil, konfirmasi lewat dialog validasi. */
(function ($) {
    'use strict';

    var $form = $('#find-form');
    var $kode = $('#kode');
    var $result = $('#result');
    var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('confirm-modal'));
    var current = null;
    var pickup = $form.data('mode') === 'pickup';

    function card(d) {
        var $card = $('<div class="card kk-result"></div>');
        var $body = $('<div class="card-body"></div>').appendTo($card);
        var $head = $('<div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2"></div>').appendTo($body);
        $head.append($('<div></div>')
            .append($('<div class="font-monospace fs-3 fw-bold"></div>').text(d.kode))
            .append($('<div></div>').text(d.nama_pelanggan)));
        $head.append($('<span class="badge fs-6"></span>').addClass(window.kkBadge(d.status)).text(d.status_label));

        var $list = $('<ul class="list-unstyled mb-3"></ul>').appendTo($body);
        $.each(d.items, function (_, item) {
            var $li = $('<li class="mb-1"></li>').text(item.qty + '× ' + item.nama);
            if (item.opsi.length) {
                $li.append($('<div class="small text-body-secondary"></div>').text(item.opsi.join(', ')));
            }
            if (item.catatan) {
                $li.append($('<div class="small text-body-secondary"></div>').text('Catatan: ' + item.catatan));
            }
            $list.append($li);
        });

        $body.append($('<div class="d-flex justify-content-between align-items-baseline border-top pt-2"></div>')
            .append($('<span></span>').text(d.metode === 'KASIR' ? 'Bayar di Kasir' : 'Bayar Online'))
            .append($('<span class="kk-price fs-4"></span>').text(rupiah(d.total))));

        if (pickup) {
            if (d.can_pickup) {
                $body.append('<button type="button" class="btn btn-success btn-lg w-100 mt-3" id="open-confirm">Konfirmasi Pengambilan</button>');
            } else {
                $body.append($('<p class="mt-3 mb-0 text-body-secondary"></p>').text(
                    d.status === 'SELESAI' ? 'Pesanan ini sudah diserahkan.' : 'Pesanan belum siap diambil.'));
            }
            if (d.receipt_url) {
                $body.append($('<a class="btn btn-outline-primary mt-2" target="_blank" rel="noopener">Cetak Struk</a>').attr('href', d.receipt_url));
            }
        } else if (d.can_confirm_payment) {
            $body.append('<button type="button" class="btn btn-success btn-lg w-100 mt-3" id="open-confirm">Konfirmasi Pembayaran</button>');
        } else {
            var note = {
                MENUNGGU_PEMBAYARAN: 'Pesanan ini memakai Bayar Online, tidak perlu konfirmasi kasir.',
                DIBATALKAN: 'Pesanan ini sudah dibatalkan.',
                KEDALUWARSA: 'Pesanan ini sudah kedaluwarsa.'
            }[d.status] || 'Pembayaran sudah dikonfirmasi.';
            $body.append($('<p class="mt-3 mb-0 text-body-secondary"></p>').text(note));
        }
        return $card;
    }

    window.kkBadge = function (status) {
        return {
            MENUNGGU_PEMBAYARAN: 'text-bg-warning', DIBAYAR: 'text-bg-success', DIPROSES: 'text-bg-primary',
            SIAP_DIAMBIL: 'text-bg-info', SELESAI: 'text-bg-secondary', DIBATALKAN: 'text-bg-danger', KEDALUWARSA: 'text-bg-dark'
        }[status] || 'text-bg-secondary';
    };

    function find(kode) {
        $kode.removeClass('is-invalid');
        $.ajax({ url: $form.data('url'), data: { kode: kode }, kkSilent: true })
            .done(function (res) {
                current = res.data;
                $result.empty().append(card(res.data));
            })
            .fail(function (xhr) {
                current = null;
                $result.empty();
                $kode.addClass('is-invalid');
                $('#kode-error').text((xhr.responseJSON && xhr.responseJSON.message) || 'Pencarian gagal, coba lagi.');
            });
    }

    $kode.on('input', function () {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });

    $form.on('submit', function (e) {
        e.preventDefault();
        var kode = $.trim($kode.val());
        if (kode.length !== 6) {
            $kode.addClass('is-invalid');
            $('#kode-error').text('Kode pesanan terdiri dari 6 karakter.');
            return;
        }
        find(kode);
    });

    $result.on('click', '#open-confirm', function () {
        $('#confirm-kode').text(current.kode);
        $('#confirm-nama').text(current.nama_pelanggan);
        $('#confirm-total').text(rupiah(current.total));
        $('#confirm-question').text(pickup
            ? 'Serahkan pesanan ini ke pelanggan yang menunjukkan kode ' + current.kode + '?'
            : 'Apakah pembayaran sebesar ' + rupiah(current.total) + ' sudah diterima di aplikasi merchant?');
        modal.show();
    });

    $('#confirm-yes').on('click', function () {
        var $button = $(this).prop('disabled', true);
        $.ajax({ url: $form.data('action-url').replace('__KODE__', current.kode), method: 'POST' })
            .done(function (res) {
                toast(pickup ? res.message : res.message + ' Pesanan ' + current.kode + ' masuk antrean.');
            })
            .always(function () {
                modal.hide();
                $button.prop('disabled', false);
                find(current.kode);
            });
    });
})(jQuery);
