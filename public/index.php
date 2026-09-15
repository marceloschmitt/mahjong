<?php

declare(strict_types=1);

if (php_sapi_name() === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if (is_file($file)) {
        return false;
    }
}

require dirname(__DIR__) . '/src/bootstrap.php';

$router = new Router();

$router->get('/login', function (): void {
    guestOnly();
    view('auth/login', [
        'title' => 'Entrar',
        'user' => null,
        'currentPath' => '/login',
        'email' => '',
    ]);
});

$router->post('/login', function (): void {
    guestOnly();
    verifyCsrf();

    $email = request('email');
    $password = $_POST['password'] ?? '';

    if (!is_string($password) || !attemptLogin($email, $password)) {
        flash('error', 'E-mail ou senha inválidos.');
        view('auth/login', [
            'title' => 'Entrar',
            'user' => null,
            'currentPath' => '/login',
            'email' => $email,
        ]);
        return;
    }

    redirect('/');
});

$router->get('/cadastro', function (): void {
    guestOnly();
    view('auth/register', [
        'title' => 'Cadastro',
        'user' => null,
        'currentPath' => '/cadastro',
        'name' => '',
        'email' => '',
        'errors' => [],
    ]);
});

$router->post('/cadastro', function (): void {
    guestOnly();
    verifyCsrf();

    $name = request('name');
    $email = request('email');
    $password = $_POST['password'] ?? '';
    $password = is_string($password) ? $password : '';

    $errors = registerUser($name, $email, $password);
    if ($errors !== []) {
        view('auth/register', [
            'title' => 'Cadastro',
            'user' => null,
            'currentPath' => '/cadastro',
            'name' => $name,
            'email' => $email,
            'errors' => $errors,
        ]);
        return;
    }

    flash('success', 'Cadastro concluído. Bem-vindo ao clube.');
    redirect('/');
});

$router->post('/logout', function (): void {
    requireAuth();
    verifyCsrf();
    logoutUser();
    redirect('/login');
});

$placeholder = static function (string $path, string $title, string $lede): void {
    $user = requireAuth();
    view('coming-soon', [
        'title' => $title,
        'heading' => $title,
        'lede' => $lede,
        'user' => $user,
        'currentPath' => $path,
    ]);
};

$router->get('/', function (): void {
    $user = requireAuth();
    $pdo = db();
    view('dashboard', [
        'title' => 'Dashboard',
        'user' => $user,
        'currentPath' => '/',
        'stats' => [
            'members' => (int) $pdo->query('SELECT COUNT(*) FROM members')->fetchColumn(),
            'events' => (int) $pdo->query('SELECT COUNT(*) FROM events')->fetchColumn(),
            'matches' => (int) $pdo->query('SELECT COUNT(*) FROM matches')->fetchColumn(),
            'progress' => (int) $pdo->query('SELECT COUNT(*) FROM progress_notes')->fetchColumn(),
        ],
    ]);
});

$router->get('/torneios', function () use ($placeholder): void {
    $placeholder('/torneios', 'Torneios', 'Competições oficiais do clube aparecerão aqui.');
});

$router->get('/eventos', function (): void {
    $user = requireAuth();
    view('events/index', [
        'title' => 'Eventos',
        'user' => $user,
        'currentPath' => '/eventos',
    ]);
});

$router->get('/partidas', function (): void {
    $user = requireAuth();
    view('matches/index', [
        'title' => 'Partidas',
        'user' => $user,
        'currentPath' => '/partidas',
    ]);
});

$router->get('/participantes', function (): void {
    $user = requireAuth();
    view('members/index', [
        'title' => 'Participantes',
        'user' => $user,
        'currentPath' => '/participantes',
    ]);
});

$router->get('/rankings', function (): void {
    $user = requireAuth();
    view('progress/index', [
        'title' => 'Rankings',
        'user' => $user,
        'currentPath' => '/rankings',
    ]);
});

$router->get('/noticias', function () use ($placeholder): void {
    $placeholder('/noticias', 'Notícias', 'Avisos e comunicados do clube ficarão neste espaço.');
});

$router->get('/usuarios', function () use ($placeholder): void {
    $placeholder('/usuarios', 'Usuários', 'Gestão de contas e papéis de acesso.');
});

$router->get('/configuracoes', function () use ($placeholder): void {
    $placeholder('/configuracoes', 'Configurações', 'Preferências do clube e do sistema.');
});

$router->get('/app', function () use ($placeholder): void {
    $placeholder('/app', 'Área do app', 'A área pública do aplicativo ainda será construída.');
});

$router->get('/membros', function (): void {
    requireAuth();
    redirect('/participantes');
});

$router->get('/amistosos', function (): void {
    requireAuth();
    redirect('/partidas');
});

$router->get('/progresso', function (): void {
    requireAuth();
    redirect('/rankings');
});

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
