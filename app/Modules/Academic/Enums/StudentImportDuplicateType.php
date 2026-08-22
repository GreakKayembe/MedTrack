<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Enums;

enum StudentImportDuplicateType: string
{
    case NONE = 'NONE';

    case SAME_UNIVERSITY_REGISTRATION =
        'SAME_UNIVERSITY_REGISTRATION';

    case EXISTING_USER =
        'EXISTING_USER';

    case EXISTING_STUDENT =
        'EXISTING_STUDENT';

    case EXISTING_ENROLLMENT =
        'EXISTING_ENROLLMENT';

    public function label(): string
    {
        return match ($this) {
            self::NONE =>
                'Aucun doublon',

            self::SAME_UNIVERSITY_REGISTRATION =>
                'Matricule déjà utilisé',

            self::EXISTING_USER =>
                'Compte utilisateur existant',

            self::EXISTING_STUDENT =>
                'Identité étudiante existante',

            self::EXISTING_ENROLLMENT =>
                'Inscription académique existante',
        };
    }

    public function blocksImport(): bool
    {
        return $this ===
            self::SAME_UNIVERSITY_REGISTRATION;
    }
}