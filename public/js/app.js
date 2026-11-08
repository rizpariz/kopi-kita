/* Utilitas bersama Kopi & Kita: CSRF untuk AJAX, penanganan galat global, toast, format Rupiah, dan polling. */
(function ($) {
    'use strict';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    });

    // Pesan sederhana di pojok kanan bawah. type: success, danger, warning, info. link opsional: {href, label}.
    window.toast = function (message, type, link) {
        var $toast = $('<div class="toast align-items-center border-0" role="status" aria-live="polite" aria-atomic="true">' +
            '<div class="d-flex"><div class="toast-body"></div>' +
            '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button></div></div>');
        $toast.addClass('text-bg-' + (type || 'success'));
        $toast.find('.toast-body').text(message);
        if (link) {
            $toast.find('.toast-body').append(' ', $('<a class="text-reset fw-semibold"></a>').attr('href', link.href).text(link.label));
        }
        $('#toast-container').append($toast);
        $toast.on('hidden.bs.toast', function () { $toast.remove(); });
        bootstrap.Toast.getOrCreateInstance($toast[0], { delay: 4000 }).show();
    };

    // 22000 menjadi "Rp22.000".
    window.rupiah = function (n) {
        var angka = Math.round(Number(n) || 0);
        return 'Rp' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    };

    // Polling berantai setTimeout: berhenti bila onData mengembalikan false, jeda saat tab tersembunyi.
    window.poll = function (url, intervalMs, onData) {
        var timer = null;
        var stopped = false;

        function schedule() {
            if (!stopped) {
                timer = setTimeout(tick, intervalMs);
            }
        }

        function tick() {
            if (document.hidden) {
                return; // dilanjutkan oleh visibilitychange
            }
            $.ajax({ url: url, method: 'GET', kkSilent: true })
                .done(function (res) {
                    if (onData(res) === false) {
                        stopped = true;
                    }
                })
                .always(schedule);
        }

        document.addEventListener('visibilitychange', function () {
            if (!document.hidden && !stopped) {
                clearTimeout(timer);
                tick();
            }
        });

        tick();
        return { stop: function () { stopped = true; clearTimeout(timer); }, now: function () { clearTimeout(timer); tick(); } };
    };

    // Galat 422 ditangani per form. Request dengan opsi kkSilent tidak menampilkan toast (dipakai polling).
    $(document).ajaxError(function (event, xhr, settings) {
        var message = (xhr.responseJSON && xhr.responseJSON.message) || null;

        if (xhr.status === 422) {
            return;
        }
        if (xhr.status === 401) {
            window.location.href = '/login';
            return;
        }
        if (xhr.status === 419) {
            toast('Sesi berakhir, halaman dimuat ulang.', 'warning');
            setTimeout(function () { window.location.reload(); }, 1500);
            return;
        }
        if (settings.kkSilent) {
            return;
        }
        if (xhr.status === 409) {
            toast(message || 'Data sudah berubah, memuat data terbaru.', 'warning');
            $(document).trigger('kk:conflict');
            return;
        }
        if (xhr.status === 403 || xhr.status === 404) {
            toast(message || 'Data tidak ditemukan atau Anda tidak memiliki akses.', 'danger');
            return;
        }
        toast(message || 'Terjadi kesalahan. Silakan coba lagi.', 'danger');
    });
})(jQuery);
