<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sanitize.css/13.0.0/sanitize.min.css">
    <link rel="stylesheet" href="{{ asset('css/reset.css')}}">
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header-item">
            <a class="home__link" href="/"></a>
            <img src="{{ asset('logo_image/logo.svg') }}" alt="header-image" class="header-logo">
        </div>
    </header>
    <div class="main__content">
        @if(session('error'))
        <div class="error-message">
            {{ session('error') }}
        </div>
        @endif
        @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
        @endif
        @if(session('status'))
        <div class="success-message">
            {{ session('status') }}
        </div>
        @endif
        @yield('content')
    </div>
    @stack('scripts')
</body>

</html>