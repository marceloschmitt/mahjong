<?php

declare(strict_types=1);

function currentUser(): ?array
{
    $id = $_SESSION['user_id'] ?? null;
    if (!is_int($id) && !is_numeric($id)) {
        return null;
    }

    $stmt = db()->prepare(
        'SELECT id, name, email, role, active FROM users WHERE id = :id AND active = 1 LIMIT 1'
    );
    $stmt->execute(['id' => (int) $id]);
    $user = $stmt->fetch();

    return $user ?: null;
}

function requireAuth(): array
{
    $user = currentUser();
    if ($user === null) {
        flash('error', 'Entre para continuar.');
        redirect('/login');
    }

    return $user;
}

function guestOnly(): void
{
    if (currentUser() !== null) {
        redirect('/');
    }
}

function attemptLogin(string $email, string $password): bool
{
    $stmt = db()->prepare(
        'SELECT id, password_hash, active FROM users WHERE email = :email LIMIT 1'
    );
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !(int) $user['active'] || !password_verify($password, $user['password_hash'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $user['id'];

    return true;
}

function registerUser(string $name, string $email, string $password): array
{
    $errors = [];

    $name = trim($name);
    $email = strtolower(trim($email));

    if ($name === '' || mb_strlen($name) < 2) {
        $errors[] = 'Informe um nome com pelo menos 2 caracteres.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Informe um e-mail válido.';
    }

    if (mb_strlen($password) < 8) {
        $errors[] = 'A senha deve ter pelo menos 8 caracteres.';
    }

    if ($errors !== []) {
        return $errors;
    }

    $exists = db()->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
    $exists->execute(['email' => $email]);
    if ($exists->fetch()) {
        return ['Este e-mail já está cadastrado.'];
    }

    $stmt = db()->prepare(
        'INSERT INTO users (name, email, password_hash, role, active)
         VALUES (:name, :email, :password_hash, :role, 1)'
    );
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'role' => 'member',
    ]);

    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) db()->lastInsertId();

    return [];
}

function logoutUser(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
}
