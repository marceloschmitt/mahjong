INSERT INTO users (name, email, password_hash, role, active)
VALUES (
    'Administrador',
    'admin@clube.local',
    '$2y$10$ClOJqlQkY/qwNV94p1aUYOs8a4lDzAjak3rbDsOSxe9Alr2WLu4Fa',
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
