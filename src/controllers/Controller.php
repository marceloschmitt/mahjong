<?php

declare(strict_types=1);

abstract class Controller
{
    protected function requireAuth(): array
    {
        return AuthService::requireUser();
    }

    protected function guestOnly(): void
    {
        AuthService::guestOnly();
    }

    /** @param array<string, mixed> $data */
    protected function view(string $name, array $data = []): void
    {
        view($name, $data);
    }
}
