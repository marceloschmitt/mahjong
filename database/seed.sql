INSERT INTO users (name, email, password_hash, role, active)
VALUES (
    'Administrador',
    'admin',
    '$2y$10$B35.L5o26El7Ar/Rt3g7i.yYe8zdatw/KRldXy7JrWrakCjLtDvRq',
    'admin',
    1
);

INSERT INTO members (user_id, nickname, joined_at, notes)
VALUES (
    1,
    'Admin',
    datetime('now'),
    'Usuário inicial de desenvolvimento.'
);
