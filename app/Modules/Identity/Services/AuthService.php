<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Services;

use MedTrack\Core\Auth\Session;
use MedTrack\Modules\Identity\Repositories\UserRepository;

final class AuthService
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly Session $session
    ) {
    }

    public function attempt(string $login, string $password): bool
    {
        $user = $this->users->findByLogin($login);

        if ($user === null) {
            return false;
        }

        if ($user['status'] !== 'ACTIVE') {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        $this->session->regenerate();

        $this->session->put(
            'auth_user_id',
            (int) $user['id']
        );

        $this->users->updateLastLogin(
            (int) $user['id']
        );

        return true;
    }

    public function check(): bool
    {
        return $this->session->has('auth_user_id');
    }

    public function id(): ?int
    {
        $id = $this->session->get('auth_user_id');

        return $id !== null
            ? (int) $id
            : null;
    }

    public function user(): ?array
    {
        $id = $this->id();

        if ($id === null) {
            return null;
        }

        return $this->users->findById($id);
    }

    public function logout(): void
    {
        $this->session->invalidate();
    }
}
