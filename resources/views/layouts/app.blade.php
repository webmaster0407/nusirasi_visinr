<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Pagrindinis') | Visi numeriai | Numeriubaze.lt</title>

    <meta name="description" content="@yield('description', 'Visi numeriai, numerių sąrašas')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="robots" content="index, follow"/>
    <meta name="google-site-verification" content="mC_Mm1KP7z8CCrkQvfQyMnfytu1vwtAIjVnwKMgIZu4" />
{{-- <link href="/your-path-to-fontawesome/css/fontawesome-all.css" rel="stylesheet"> <!--load all styles --> --}}
    @if(View::hasSection('canonical'))
        <link rel="canonical" href="@yield('canonical')" />
    @endif

    <link rel="alternate" href="{{ Request::fullUrl() }}" hreflang="lt" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ Request::fullUrl() }}" />
    <meta property="og:title" content="@yield('title', 'Kas skambino') | Visi numeriai | Numeriubaze.lt" />
    <meta property="og:description" content="@yield('description', '')" />
    <meta property="og:image" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
   <!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/fontawesome.min.css" />  -->   
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" /> -->
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" />

    @yield('css')

    <script src="{{ elixir('js/app.js') }}"></script>         

    @yield('js')
</head>
<body>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="{{ url('/') }}">
                Numeriubaze.lt
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <ul class="nav navbar-nav"></ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ route('index') }}">Telefono numerių kodai</a></li>
				<li><a href="{{ route('index') }}">Ieškomiausi telefonų numeriai</a></li>
				<li><a href="{{ route('index') }}">D.U.K.</a></li>
				<li><a href="{{ route('comments') }}">Paskutiniai komentarai</a></li> 
				<li><a href="{{ route('contact') }}">Kontaktai</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            
            @yield('content')
        </div>
    </div>
</div>
@include('_partials._footer')
@include('_partials.google_analytics')
</body>
</html>
