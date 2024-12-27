<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreeMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sanitize.css/13.0.0/sanitize.min.css">
    @yield('css')
</head>
<!-- @if(view()->yieldContent('page') === 'admin') @endif-->

<body>
    <header class="header">
        <h1><img src="" alt="header-image"></h1>
        <input class="header-search__input">
        <nav>
            <ul class="nav-list">
                <li><a href="/logout">ログアウト</a></li>
                <li><a href="/my-page">マイページ</a></li>
                <li><a href="/"></a></li>
            </ul>
        </nav>
    </header>
    <div class="page-list">
    
    </div>
    <div class="main__content">
        @yield('content')
    </div>
    @stack('script')
</body>

</html>