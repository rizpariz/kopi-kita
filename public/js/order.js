/* Halaman status pesanan: polling 5 detik, tampilan berganti tanpa reload, hitung mundur batas bayar. */
(function ($) {
    'use strict';

    var $page = $('#order-page');
    var steps = $('#order-steps [data-step]').map(function () { return $(this).data('step'); }).get();
    var countdownTimer = null;

    function render(status, alasan) {
        var info = window.kkStatuses[status];
        $page.attr('data-status', status);
        $('#order-badge').attr('class', 'badge fs-6 ' + info.badge).text(info.label);

        $('[data-states]').each(function () {
            $(this).toggleClass('d-none', $(this).attr('data-states').split(' ').indexOf(status) === -1);
        });

        var current = steps.indexOf(status);
        $('#order-steps [data-step]').each(function (i) {
            $(this).toggleClass('is-done', i < current).toggleClass('is-current', i === current)
                .attr('aria-current', i === current ? 'step' : null);
        });

        if (alasan) {
            $('#order-reason').text('Alasan dari kedai: ' + alasan);
        }
        if (status !== 'MENUNGGU_PEMBAYARAN') {
            clearInterval(countdownTimer);
        }
    }

    function tickCountdown() {
        var expired = Date.parse($page.data('expired'));
        if (!expired) {
            return;
        }
        var seconds = Math.max(0, Math.floor((expired - Date.now()) / 1000));
        var h = Math.floor(seconds / 3600);
        var m = Math.floor((seconds % 3600) / 60);
        var s = seconds % 60;
        $('#countdown').text('(sisa ' + (h ? h + ' jam ' + m + ' menit' : m + ':' + String(s).padStart(2, '0')) + ')');
    }

    render($page.attr('data-status'));

    $('#copy-code').on('click', function () {
        var code = $(this).data('code');
        navigator.clipboard.writeText(code)
            .then(function () { toast('Kode ' + code + ' disalin.'); })
            .catch(function () { toast('Salin manual: ' + code, 'info'); });
    });

    if ($page.attr('data-status') === 'MENUNGGU_PEMBAYARAN') {
        tickCountdown();
        countdownTimer = setInterval(tickCountdown, 1000);
    }

    if (!window.kkStatuses[$page.attr('data-status')] || ['SELESAI', 'DIBATALKAN', 'KEDALUWARSA'].indexOf($page.attr('data-status')) !== -1) {
        return;
    }

    function onStatus(res) {
        var d = res.data;
        if (d.status !== $page.attr('data-status')) {
            render(d.status, d.alasan_batal);
            if (d.status === 'DIBAYAR') {
                toast('Pembayaran berhasil.');
            }
        }
        return d.is_final ? false : undefined;
    }

    var poller = poll($page.data('status-url'), 5000, onStatus);

    // Cek Status meminta server menanyakan Status API Midtrans saat itu juga.
    $('#check-status').on('click', function () {
        var $button = $(this).prop('disabled', true);
        $.getJSON($page.data('status-url'), { cek: 1 })
            .done(function (res) {
                if (onStatus(res) === false) {
                    poller.stop();
                } else if (res.data.status === 'MENUNGGU_PEMBAYARAN') {
                    toast('Pembayaran belum diterima.', 'info');
                }
            })
            .always(function () { $button.prop('disabled', false); });
    });

    // Snap: callback hanya memicu cek status atau pesan; status resmi selalu dari server (webhook atau Status API).
    function payOnline() {
        var $buttons = $('.js-pay-online').prop('disabled', true);
        $.ajax({ url: $page.data('pay-url'), method: 'POST' })
            .done(function (res) {
                $page.data('expired', res.data.expired_at);
                if ($page.data('metode') !== 'ONLINE') {
                    window.location.href = window.location.pathname + '?bayar=1';
                    return;
                }
                window.snap.pay(res.data.snap_token, {
                    onSuccess: function () { toast('Pembayaran diterima, memeriksa status.'); poller.now(); },
                    onPending: function () { toast('Menunggu pembayaran selesai.', 'info'); poller.now(); },
                    onError: function () { toast('Pembayaran gagal. Coba lagi atau ganti ke Bayar di Kasir.', 'danger'); },
                    onClose: function () { toast('Jendela pembayaran ditutup. Tekan Lanjutkan Pembayaran untuk membukanya lagi.', 'info'); poller.now(); }
                });
            })
            .always(function () { $buttons.prop('disabled', false); });
    }

    $('.js-pay-online').on('click', payOnline);

    if (new URLSearchParams(window.location.search).get('bayar') === '1' && window.snap) {
        history.replaceState(null, '', window.location.pathname);
        payOnline();
    }

    $('#switch-cashier').on('click', function () {
        var $button = $(this).prop('disabled', true);
        $.ajax({ url: $button.data('url'), method: 'POST' })
            .done(function () { window.location.reload(); })
            .fail(function () { $button.prop('disabled', false); });
    });
})(jQuery);
