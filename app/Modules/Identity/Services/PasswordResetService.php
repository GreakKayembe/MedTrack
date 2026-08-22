<?php

declare(strict_types=1);

namespace MedTrack\Modules\Identity\Services;

use MedTrack\Modules\Identity\Repositories\PasswordResetRepository;
use MedTrack\Modules\Identity\Repositories\UserRepository;
use RuntimeException;

final class PasswordResetService
{
    private const CHANNEL_EMAIL = 'EMAIL';

    private const TOKEN_LIFETIME_MINUTES = 30;

    private const MAX_ATTEMPTS = 5;

    public function __construct(
        private readonly UserRepository $users,
        private readonly PasswordResetRepository $tokens
    ) {
    }

    public function requestEmailReset(
        string $identifier,
        ?string $ipAddress = null
    ): ?string {
        $identifier = trim($identifier);

        if ($identifier === '') {
            return null;
        }

        $user = $this->users->findForPasswordReset(
            $identifier
        );

        /*
         * Anti-enumeration :
         * le contrôleur donnera exactement la même réponse
         * que le compte existe ou non.
         */
        if (
            $user === null
            || $user['status'] !== 'ACTIVE'
            || empty($user['email'])
        ) {
            return null;
        }

        $userId = (int) $user['id'];

        /*
         * Un nouveau reset révoque les précédents
         * pour le même utilisateur/canal.
         */
        $this->tokens->revokeActiveForUser(
            $userId,
            self::CHANNEL_EMAIL
        );

        /*
         * 256 bits d'entropie.
         */
        $rawToken = bin2hex(
            random_bytes(32)
        );

        /*
         * La base ne reçoit jamais le token utilisable.
         */
        $tokenHash = hash(
            'sha256',
            $rawToken
        );

        

        $this->tokens->create(
            $userId,
            self::CHANNEL_EMAIL,
            $tokenHash,
            $ipAddress
        );

        return $rawToken;
    }

    public function validateToken(
        string $rawToken
    ): bool {
        if (!$this->hasValidFormat($rawToken)) {
            return false;
        }

        $tokenHash = hash(
            'sha256',
            $rawToken
        );

        $record = $this->tokens->findValidByHash(
            $tokenHash
        );

        if ($record === null) {
            return false;
        }

        if (
            (int) $record['attempt_count']
            >= self::MAX_ATTEMPTS
        ) {
            $this->tokens->revoke(
                (int) $record['id']
            );

            return false;
        }

        return true;
    }

    public function resetPassword(
        string $rawToken,
        string $newPassword
    ): void {
        if (!$this->hasValidFormat($rawToken)) {
            throw new RuntimeException(
                'Lien de réinitialisation invalide.'
            );
        }

        $tokenHash = hash(
            'sha256',
            $rawToken
        );

        $record = $this->tokens->findValidByHash(
            $tokenHash
        );

        if ($record === null) {
            throw new RuntimeException(
                'Ce lien est invalide ou a expiré.'
            );
        }

        $tokenId = (int) $record['id'];

        $attempts = (int) $record['attempt_count'];

        if ($attempts >= self::MAX_ATTEMPTS) {
            $this->tokens->revoke($tokenId);

            throw new RuntimeException(
                'Ce lien de réinitialisation a été révoqué.'
            );
        }

        /*
         * La tentative est comptabilisée avant
         * l'opération sensible.
         */
        $this->tokens->incrementAttempts(
            $tokenId
        );

        $this->validatePassword(
            $newPassword
        );

        $passwordHash = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        if ($passwordHash === false) {
            throw new RuntimeException(
                'Impossible de sécuriser le nouveau mot de passe.'
            );
        }

        $this->users->updatePassword(
            (int) $record['user_id'],
            $passwordHash
        );

        /*
         * Usage unique.
         */
        $this->tokens->markAsUsed(
            $tokenId
        );
    }

    private function hasValidFormat(
        string $token
    ): bool {
        return preg_match(
            '/^[a-f0-9]{64}$/',
            $token
        ) === 1;
    }

    private function validatePassword(
        string $password
    ): void {
        if (strlen($password) < 12) {
            throw new RuntimeException(
                'Le mot de passe doit contenir au moins 12 caractères.'
            );
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw new RuntimeException(
                'Le mot de passe doit contenir au moins une majuscule.'
            );
        }

        if (!preg_match('/[a-z]/', $password)) {
            throw new RuntimeException(
                'Le mot de passe doit contenir au moins une minuscule.'
            );
        }

        if (!preg_match('/[0-9]/', $password)) {
            throw new RuntimeException(
                'Le mot de passe doit contenir au moins un chiffre.'
            );
        }

        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            throw new RuntimeException(
                'Le mot de passe doit contenir au moins un caractère spécial.'
            );
        }
    }
}