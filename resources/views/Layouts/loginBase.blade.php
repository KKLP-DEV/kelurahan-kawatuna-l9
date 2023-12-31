<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <link href="{{ asset('img/favicon_palukota.png') }}" rel="icon">
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">

    <title>Kelurahan Kawatuna</title>
</head>

<body>
    @yield('content')
    @include('sweetalert::alert')

</body>

</html>
