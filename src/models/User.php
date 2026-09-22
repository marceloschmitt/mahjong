<?php

declare(strict_types=1);

final class User
{
    public static function findActiveById(int $id): ?array
    {
        $stmt = db()->prepare(
            'SELECT id, name, email, role, active FROM users WHERE id = :id AND active = 1 LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = db()->prepare(
            'SELECT id, password_hash, active FROM users WHERE email = :email LIMIT 1'
        );
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public static function emailExists(string $email): bool
    {
        $stmt = db()->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        return (bool) $stmt->fetch();
    }

    public static function create(string $name, string $email, string $password, string $role = 'member'): int
    {
        $stmt = db()->prepare(
            'INSERT INTO users (name, email, password_hash, role, active)
             VALUES (:name, :email, :password_hash, :role, 1)'
        );
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
        ]);

        return (int) db()->lastInsertId();
    }

    /** @return list<string> */
    public static function register(string $name, string $email, string $password): array
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

        if (self::emailExists($email)) {
            return ['Este e-mail já está cadastrado.'];
        }

        $id = self::create($name, $email, $password);
        AuthService::login($id);

        return [];
    }
}
