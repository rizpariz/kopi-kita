/* Antrean barista: polling 10 detik, render ulang hanya bila data berubah, kartu baru disorot. */
(function ($) {
    'use strict';

    var $root = $('#queue');
    var lastJson = null;
    var known = null;

    function time(iso) {
        return iso ? new Date(iso).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : '';
    }

    function card(order, isNew) {
        var $card = $('<article class="card kk-order-card h-100"></article>').attr('data-kode', order.kode).toggleClass('is-new', isNew);
        var $body = $('<div class="card-body d-flex flex-column"></div>').appendTo($card);

        $('<div class="d-flex justify-content-between align-items-start gap-2 mb-1"></div>')
            .append($('<span class="kk-order-code"></span>').text(order.kode))
            .append($('<span class="badge"></span>').addClass(order.status === 'DIPROSES' ? 'text-bg-primary' : (order.status === 'SIAP_DIAMBIL' ? 'text-bg-info' : 'text-bg-success')).text(order.status_label))
            .appendTo($body);
        $('<p class="small text-body-secondary mb-2"></p>')
            .text(order.nama_pelanggan + ', ' + (order.status === 'SIAP_DIAMBIL' ? 'siap ' + time(order.ready_at) : 'bayar ' + time(order.paid_at)) + (order.metode === 'KASIR' ? ' (kasir)' : ''))
            .appendTo($body);

        if (order.status !== 'SIAP_DIAMBIL') {
            var $list = $('<ul class="list-unstyled mb-3"></ul>').appendTo($body);
            $.each(order.items, function (_, item) {
                var $li = $('<li class="kk-order-item"></li>').append($('<strong></strong>').text(item.qty + '× ' + item.nama));
                if (item.opsi.length) {
                    $li.append($('<div class="small"></div>').text(item.opsi.join(', ')));
                }
                if (item.catatan) {
                    $li.append($('<div class="small kk-note"></div>').text('Catatan: ' + item.catatan));
                }
                $list.append($li);
            });
            var next = order.status === 'DIBAYAR' ? ['DIPROSES', 'Proses'] : ['SIAP_DIAMBIL', 'Siap Diambil'];
            $('<button type="button" class="btn btn-primary mt-auto js-advance"></button>')
                .attr({ 'data-kode': order.kode, 'data-next': next[0] })
                .addClass(next[0] === 'SIAP_DIAMBIL' ? 'btn-success' : '')
                .text(next[1])
                .appendTo($body);
        }
        return $card;
    }

    function render(data) {
        var codes = data.antrean.concat(data.siap_diambil).map(function (o) { return o.kode + o.status; });
        var $antrean = $('#list-antrean').empty();
        var $siap = $('#list-siap').empty();

        $.each(data.antrean, function (_, o) {
            $antrean.append($('<div class="col"></div>').append(card(o, !!known && known.indexOf(o.kode + o.status) === -1)));
        });
        $.each(data.siap_diambil, function (_, o) {
            $siap.append(card(o, !!known && known.indexOf(o.kode + o.status) === -1));
        });

        $('#count-antrean').text(data.antrean.length);
        $('#count-siap').text(data.siap_diambil.length);
        $('#empty-antrean').toggleClass('d-none', data.antrean.length > 0);
        $('#empty-siap').toggleClass('d-none', data.siap_diambil.length > 0);
        known = codes;
    }

    var poller = poll($root.data('url'), 10000, function (res) {
        var json = JSON.stringify([res.data.antrean, res.data.siap_diambil]);
        $('#queue-updated').text('Diperbarui ' + time(res.data.generated_at));
        if (json !== lastJson) {
            lastJson = json;
            render(res.data);
        }
    });

    $root.on('click', '.js-advance', function () {
        var $button = $(this).prop('disabled', true);
        $.ajax({
            url: $root.data('status-url').replace('__KODE__', $button.data('kode')),
            method: 'PATCH',
            data: { status: $button.data('next') }
        }).done(function (res) {
            toast($button.data('kode') + ': ' + res.data.status_label);
        }).always(function () {
            poller.now();
        });
    });

    $(document).on('kk:conflict', function () { poller.now(); });
})(jQuery);
