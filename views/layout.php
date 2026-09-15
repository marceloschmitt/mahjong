<?php
/** @var string $title */
/** @var string $content */
/** @var string $appName */
/** @var array|null $user */
/** @var array|null $flash */
/** @var string $currentPath */
$isGuest = $user === null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> · <?= e($appName) ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="<?= $isGuest ? 'is-guest' : 'is-app' ?>">
    <div class="shell">
        <header class="topbar">
            <a class="brand" href="<?= $isGuest ? '/login' : '/' ?>">
                <span class="brand-mark" aria-hidden="true">雀</span>
                <span class="brand-text"><?= e($appName) ?></span>
            </a>

            <?php if (!$isGuest): ?>
                <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-nav">Menu</button>
                <nav id="main-nav" class="nav">
                    <a href="/" class="<?= isActivePath($currentPath, '/') ? 'is-active' : '' ?>">Painel</a>
                    <a href="/membros" class="<?= isActivePath($currentPath, '/membros') ? 'is-active' : '' ?>">Membros</a>
                    <a href="/eventos" class="<?= isActivePath($currentPath, '/eventos') ? 'is-active' : '' ?>">Eventos</a>
                    <a href="/amistosos" class="<?= isActivePath($currentPath, '/amistosos') ? 'is-active' : '' ?>">Amistosos</a>
                    <a href="/progresso" class="<?= isActivePath($currentPath, '/progresso') ? 'is-active' : '' ?>">Progresso</a>
                </nav>
                <div class="session">
                    <span class="session-name"><?= e($user['name']) ?></span>
                    <form method="post" action="/logout">
                        <?= csrfField() ?>
                        <button type="submit" class="link-button">Sair</button>
                    </form>
                </div>
            <?php endif; ?>
        </header>

        <?php if ($flash): ?>
            <div class="flash flash-<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <main class="main">
            <?= $content ?>
        </main>

        <footer class="footer">
            <p><?= e($appName) ?> · gestão de clube</p>
        </footer>
    </div>
    <script src="/assets/js/app.js"></script>
</body>
</html>
