<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'App') ?> - <?=  e(config('app', 'name')) ?>?>?></title>
    <link rel="stylesheet" href="/public/css/app.css">
</head>
<body>
    <nav class="navbar">
        <a href="/" class="brand"><?= e(config('app', 'name')) ?></a>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/users">Users</a></li>
            <li><a href="/about">About</a></li>
        </ul>
    </nav>

    <main class="container">
        <?php if ($msg = flash('success')): ?>
            <div class="alert alert-success"><?= e($msg) ?></div>
            <?php endif; ?>
        
        <?php if ($msg = flash('error')): ?>
            <div class="alert alert-error"><?= e($msg) ?></div>
            <?php endif; ?>

        <?= $content ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> <?= e($config('app', 'name')) ?></p>
    </footer>

    <script src="/public/js/app.js"></script>
</body>
</html>