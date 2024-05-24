<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" value="{{ csrf_token() }}" />

    <title>Seleccion RA</title>

    <link href="{{ asset(mix('css/app.css')) }}?v1.2" type="text/css" rel="stylesheet" />

</head>

<body>

    <div id="app"></div>
    <script src="{{ asset(mix('js/app.js')) }}?v1.5" type="text/javascript"></script>
    <script src="{{ asset(mix('js/plugins.js')) }}?v1.5" type="text/javascript"></script>
</body>

</html>