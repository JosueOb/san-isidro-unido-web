<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    
    <!-- Icon-->
    <link rel="icon" type="image/png" href="https://siu-resources-s3.s3.us-east-2.amazonaws.com/default_images/logos/logo.png">

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="{{ mix('css/app.css') }}">
      
</head>
<body>
    
</body>
</html>