<?php

declare(strict_types=1);

namespace MedTrack\Core\Security\RateLimit;

use InvalidArgumentException;

final class RateLimiter
{
    public const LOGIN = 'LOGIN';

    public const PASSWORD_RESET = 'PASSWORD_RESET';

    private const POLICIES = [
        self::LOGIN => [
            'max_attempts' => 5,
            'window_minutes' => 15,
            'block_minutes' => 15,
        ],

        self::PASSWORD_RESET => [
            'max_attempts' => 3,
            'window_minutes' => 30,
            'block_minutes' => 30,
        ],
    ];

    public function __construct(
        private readonly RateLimitRepository $repository
    ) {
    }

    public function check(
        string $action,
        string $identifier,
        string $ipAddress
    ): RateLimitResult {
        $policy = $this->policy($action);

        $identifierHash = $this->hashIdentifier(
            $identifier
        );

        $record = $this->repository->find(
            $action,
            $identifierHash,
            $ipAddress
        );

        if ($record === null) {
            return RateLimitResult::allowed();
        }

        /*
         * Blocage toujours actif.
         */
        if ((int) $record['is_blocked'] === 1) {
            return RateLimitResult::blocked(
                (int) $record['retry_after']
            );
        }

        /*
         * Le blocage existait mais vient d'expirer.
         */
        if ($record['blocked_until'] !== null) {
            $this->repository->resetWindow(
                (int) $record['id']
            );

            return RateLimitResult::allowed();
        }

        /*
         * Vérification de la fenêtre temporelle.
         */
        if (
            $this->repository->isWindowExpired(
                (int) $record['id'],
                $policy['window_minutes']
            )
        ) {
            $this->repository->resetWindow(
                (int) $record['id']
            );
        }

        return RateLimitResult::allowed();
    }

    public function hit(
        string $action,
        string $identifier,
        string $ipAddress
    ): void {
        $policy = $this->policy($action);

        $identifierHash = $this->hashIdentifier(
            $identifier
        );

        $record = $this->repository->find(
            $action,
            $identifierHash,
            $ipAddress
        );

        /*
         * Première tentative.
         */
        if ($record === null) {
            $this->repository->create(
                $action,
                $identifierHash,
                $ipAddress
            );

            if ($policy['max_attempts'] <= 1) {
                $record = $this->repository->find(
                    $action,
                    $identifierHash,
                    $ipAddress
                );

                if ($record !== null) {
                    $this->repository->block(
                        (int) $record['id'],
                        $policy['block_minutes']
                    );
                }
            }

            return;
        }

        /*
         * Si un blocage est encore actif,
         * nous n'incrémentons plus le compteur.
         */
        if ((int) $record['is_blocked'] === 1) {
            return;
        }

        /*
         * Blocage expiré : nouvelle fenêtre.
         */
        if ($record['blocked_until'] !== null) {
            $this->repository->resetWindow(
                (int) $record['id']
            );

            return;
        }

        /*
         * Fenêtre normale expirée.
         */
        if (
            $this->repository->isWindowExpired(
                (int) $record['id'],
                $policy['window_minutes']
            )
        ) {
            $this->repository->resetWindow(
                (int) $record['id']
            );

            return;
        }

        $newAttemptCount =
            (int) $record['attempt_count'] + 1;

        $this->repository->increment(
            (int) $record['id']
        );

        /*
         * La limite atteinte déclenche immédiatement
         * le blocage des tentatives suivantes.
         */
        if (
            $newAttemptCount
            >= $policy['max_attempts']
        ) {
            $this->repository->block(
                (int) $record['id'],
                $policy['block_minutes']
            );
        }
    }

    public function clear(
        string $action,
        string $identifier,
        string $ipAddress
    ): void {
        $this->repository->clear(
            $action,
            $this->hashIdentifier($identifier),
            $ipAddress
        );
    }

    private function hashIdentifier(
        string $identifier
    ): string {
        return hash(
            'sha256',
            mb_strtolower(
                trim($identifier),
                'UTF-8'
            )
        );
    }

    private function policy(
        string $action
    ): array {
        if (!isset(self::POLICIES[$action])) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unsupported rate limit action: %s',
                    $action
                )
            );
        }

        return self::POLICIES[$action];
    }
}