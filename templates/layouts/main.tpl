<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{block name="title"}Блог о фотохостинге{/block}</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner">
            <a class="site-header__brand" href="/">ФотоБлог</a>
        </div>
    </header>

    <main class="container">
        {block name="content"}{/block}
    </main>
</body>
</html>
