<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services;

use MedTrack\Modules\Identity\Repositories\OrganizationOnboardingRepository;
use PDO;
use RuntimeException;
use Throwable;

final class UniversityOnboardingService
{
    private const UNIVERSITY_ADMIN_ROLE =
        'UNIVERSITY_ADMIN';

    private const UNIVERSITY_TYPE =
        'UNIVERSITY';

    public function __construct(
        private readonly PDO $pdo,
        private readonly UniversityService $universities,
        private readonly OrganizationOnboardingRepository $onboarding
    ) {
    }

    /**
     * Crée une université ainsi que son
     * administrateur principal.
     *
     * Toutes les opérations sont exécutées
     * dans une transaction unique.
     *
     * @return array{
     *     university_id:int,
     *     user_id:int,
     *     membership_id:int,
     *     temporary_password:string,
     *     administrator_email:string
     * }
     */
    public function createUniversityWithAdministrator(
        array $universityData,
        array $administratorData
    ): array {
        $administrator =
            $this->validateAdministrator(
                $administratorData
            );

        /*
        |--------------------------------------------------------------------------
        | Uniqueness checks
        |--------------------------------------------------------------------------
        */

        if (
            $this->onboarding
                ->emailExists(
                    $administrator['email']
                )
        ) {
            throw new RuntimeException(
                'Un utilisateur utilise déjà '
                . 'cette adresse email.'
            );
        }

        if (
            $administrator['phone'] !== null
            && $this->onboarding
                ->phoneExists(
                    $administrator['phone']
                )
        ) {
            throw new RuntimeException(
                'Un utilisateur utilise déjà '
                . 'ce numéro de téléphone.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Role
        |--------------------------------------------------------------------------
        |
        | On vérifie le rôle avant de commencer
        | les insertions.
        |--------------------------------------------------------------------------
        */

        $role =
            $this->onboarding
                ->findOrganizationRoleByCode(
                    self::UNIVERSITY_ADMIN_ROLE,
                    self::UNIVERSITY_TYPE
                );

        if ($role === null) {
            throw new RuntimeException(
                'Le rôle UNIVERSITY_ADMIN '
                . 'est introuvable.'
            );
        }

        $roleId =
            (int) (
                $role['id']
                ?? 0
            );

        if ($roleId <= 0) {
            throw new RuntimeException(
                'Le rôle UNIVERSITY_ADMIN '
                . 'est invalide.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Credentials
        |--------------------------------------------------------------------------
        */

        $temporaryPassword =
            $this->generateTemporaryPassword();

        $passwordHash =
            password_hash(
                $temporaryPassword,
                PASSWORD_DEFAULT
            );

        if (!is_string($passwordHash)) {
            throw new RuntimeException(
                'Impossible de sécuriser '
                . 'le mot de passe temporaire.'
            );
        }

        $userUuid =
            $this->generateUuidV4();

        /*
        |--------------------------------------------------------------------------
        | Transaction
        |--------------------------------------------------------------------------
        */

        if ($this->pdo->inTransaction()) {
            throw new RuntimeException(
                'Une transaction est déjà active '
                . 'avant l’onboarding universitaire.'
            );
        }

        $this->pdo->beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | 1. University
            |--------------------------------------------------------------------------
            |
            | UniversityRepository est désormais transaction-aware.
            | Il ne commit donc pas si cette transaction est active.
            |--------------------------------------------------------------------------
            */

            $universityId =
                $this->universities
                    ->create(
                        $universityData
                    );

            if ($universityId <= 0) {
                throw new RuntimeException(
                    'L’université n’a pas pu être créée.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 2. User
            |--------------------------------------------------------------------------
            */

            $userId =
                $this->onboarding
                    ->createUser(
                        uuid:
                            $userUuid,

                        email:
                            $administrator['email'],

                        phone:
                            $administrator['phone'],

                        passwordHash:
                            $passwordHash
                    );

            /*
            |--------------------------------------------------------------------------
            | 3. Profile
            |--------------------------------------------------------------------------
            */

            $this->onboarding
                ->createUserProfile(
                    userId:
                        $userId,

                    firstName:
                        $administrator['first_name'],

                    middleName:
                        $administrator['middle_name'],

                    lastName:
                        $administrator['last_name']
                );

            /*
            |--------------------------------------------------------------------------
            | 4. Organization membership
            |--------------------------------------------------------------------------
            */

            $membershipId =
                $this->onboarding
                    ->createMembership(
                        organizationId:
                            $universityId,

                        userId:
                            $userId
                    );

            /*
            |--------------------------------------------------------------------------
            | 5. UNIVERSITY_ADMIN role
            |--------------------------------------------------------------------------
            */

            $this->onboarding
                ->assignMembershipRole(
                    membershipId:
                        $membershipId,

                    roleId:
                        $roleId
                );

            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            $this->pdo->commit();

            return [
                'university_id' =>
                    $universityId,

                'user_id' =>
                    $userId,

                'membership_id' =>
                    $membershipId,

                'temporary_password' =>
                    $temporaryPassword,

                'administrator_email' =>
                    $administrator['email'],
            ];
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Administrator validation
    |--------------------------------------------------------------------------
    */

    /**
     * @return array{
     *     first_name:string,
     *     middle_name:?string,
     *     last_name:string,
     *     email:string,
     *     phone:?string
     * }
     */
    private function validateAdministrator(
        array $data
    ): array {
        $firstName =
            trim(
                (string) (
                    $data['first_name']
                    ?? ''
                )
            );

        $middleName =
            trim(
                (string) (
                    $data['middle_name']
                    ?? ''
                )
            );

        $lastName =
            trim(
                (string) (
                    $data['last_name']
                    ?? ''
                )
            );

        $email =
            strtolower(
                trim(
                    (string) (
                        $data['email']
                        ?? ''
                    )
                )
            );

        $phone =
            trim(
                (string) (
                    $data['phone']
                    ?? ''
                )
            );

        /*
        |--------------------------------------------------------------------------
        | First name
        |--------------------------------------------------------------------------
        */

        if ($firstName === '') {
            throw new RuntimeException(
                'Le prénom de l’administrateur '
                . 'est obligatoire.'
            );
        }

        if (mb_strlen($firstName) > 150) {
            throw new RuntimeException(
                'Le prénom de l’administrateur '
                . 'ne peut pas dépasser 150 caractères.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Middle name
        |--------------------------------------------------------------------------
        */

        if (
            $middleName !== ''
            && mb_strlen($middleName) > 150
        ) {
            throw new RuntimeException(
                'Le deuxième prénom '
                . 'ne peut pas dépasser 150 caractères.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Last name
        |--------------------------------------------------------------------------
        */

        if ($lastName === '') {
            throw new RuntimeException(
                'Le nom de l’administrateur '
                . 'est obligatoire.'
            );
        }

        if (mb_strlen($lastName) > 150) {
            throw new RuntimeException(
                'Le nom de l’administrateur '
                . 'ne peut pas dépasser 150 caractères.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Email
        |--------------------------------------------------------------------------
        */

        if ($email === '') {
            throw new RuntimeException(
                'L’adresse email de l’administrateur '
                . 'est obligatoire.'
            );
        }

        if (
            filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            ) === false
        ) {
            throw new RuntimeException(
                'L’adresse email de l’administrateur '
                . 'est invalide.'
            );
        }

        if (mb_strlen($email) > 190) {
            throw new RuntimeException(
                'L’adresse email de l’administrateur '
                . 'est trop longue.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Phone
        |--------------------------------------------------------------------------
        */

        if (
            $phone !== ''
            && mb_strlen($phone) > 30
        ) {
            throw new RuntimeException(
                'Le numéro de téléphone '
                . 'est trop long.'
            );
        }

        return [
            'first_name' =>
                $firstName,

            'middle_name' =>
                $middleName !== ''
                    ? $middleName
                    : null,

            'last_name' =>
                $lastName,

            'email' =>
                $email,

            'phone' =>
                $phone !== ''
                    ? $phone
                    : null,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Temporary password
    |--------------------------------------------------------------------------
    */

    /**
     * Génère un mot de passe temporaire
     * suffisamment robuste pour l'onboarding.
     *
     * Le mot de passe n'est jamais persisté
     * en clair dans la base de données.
     */
    private function generateTemporaryPassword(): string
    {
        $upper =
            'ABCDEFGHJKLMNPQRSTUVWXYZ';

        $lower =
            'abcdefghijkmnopqrstuvwxyz';

        $digits =
            '23456789';

        $symbols =
            '!@#$%*-_';

        $all =
            $upper
            . $lower
            . $digits
            . $symbols;

        $password = [
            $upper[
                random_int(
                    0,
                    strlen($upper) - 1
                )
            ],

            $lower[
                random_int(
                    0,
                    strlen($lower) - 1
                )
            ],

            $digits[
                random_int(
                    0,
                    strlen($digits) - 1
                )
            ],

            $symbols[
                random_int(
                    0,
                    strlen($symbols) - 1
                )
            ],
        ];

        while (count($password) < 14) {
            $password[] =
                $all[
                    random_int(
                        0,
                        strlen($all) - 1
                    )
                ];
        }

        /*
         * Fisher-Yates sécurisé avec random_int.
         */
        for (
            $i = count($password) - 1;
            $i > 0;
            $i--
        ) {
            $j =
                random_int(
                    0,
                    $i
                );

            [
                $password[$i],
                $password[$j],
            ] = [
                $password[$j],
                $password[$i],
            ];
        }

        return implode(
            '',
            $password
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UUID
    |--------------------------------------------------------------------------
    */

    private function generateUuidV4(): string
    {
        $data =
            random_bytes(
                16
            );

        $data[6] =
            chr(
                (
                    ord(
                        $data[6]
                    )
                    & 0x0f
                )
                | 0x40
            );

        $data[8] =
            chr(
                (
                    ord(
                        $data[8]
                    )
                    & 0x3f
                )
                | 0x80
            );

        return vsprintf(
            '%s%s-%s-%s-%s-%s%s%s',
            str_split(
                bin2hex(
                    $data
                ),
                4
            )
        );
    }
}