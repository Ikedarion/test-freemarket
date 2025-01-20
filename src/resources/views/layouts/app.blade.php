<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeMarket</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css')}}">
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header-item">
            <a class="home__link" href="/"></a>
            <img src="{{ asset('logo_image/logo.svg') }}" alt="header-image" class="header-logo">
            <form action="{{ request()->is('mypage*') ? route('my-page', ['tab' => request('tab')]) : route('home') }}" method="get" class="search-form">
                <input name="keyword" class="header-search__input" type="text" placeholder="何をお探しですか？">
                <input type="hidden" name="page" value="{{ request('tab') }}">
            </form>
        </div>
        <nav>
            <ul>
                <li>
                    <form class="logout-form" action="/logout" method="POST">
                        @csrf
                        <input class="nav-list-logout" type="submit" value="ログアウト">
                    </form>
                </li>
                <li>
                    <a class="nav-list" href="/mypage">マイページ</a>
                </li>
                <li>
                    <a class="nav-list-btn" href="/sell">出品</a>
                </li>
            </ul>
        </nav>
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
        @yield('content')
    </div>
    @stack('scripts')
</body>

</html>