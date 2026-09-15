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

$router->get('/', function (): void {
    $user = requireAuth();
    $pdo = db();
    view('dashboard', [
        'title' => 'Painel',
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

$router->get('/membros', function (): void {
    $user = requireAuth();
    view('members/index', [
        'title' => 'Membros',
        'user' => $user,
        'currentPath' => '/membros',
    ]);
});

$router->get('/eventos', function (): void {
    $user = requireAuth();
    view('events/index', [
        'title' => 'Eventos',
        'user' => $user,
        'currentPath' => '/eventos',
    ]);
});

$router->get('/amistosos', function (): void {
    $user = requireAuth();
    view('matches/index', [
        'title' => 'Amistosos',
        'user' => $user,
        'currentPath' => '/amistosos',
    ]);
});

$router->get('/progresso', function (): void {
    $user = requireAuth();
    view('progress/index', [
        'title' => 'Progresso',
        'user' => $user,
        'currentPath' => '/progresso',
    ]);
});

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
