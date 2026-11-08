{{-- Layout cetak struk thermal 58 mm. --}}
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Struk') | {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
</head>
<body>
    <div class="no-print toolbar">
        <button type="button" onclick="window.print()">Cetak Struk</button>
        <button type="button" onclick="history.back()">Kembali</button>
    </div>
    <div class="struk">
        @yield('content')
    </div>
</body>
</html>
