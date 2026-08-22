<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Enums;

enum StudentImportRowStatus: string
{
    case VALID = 'VALID';

    case WARNING = 'WARNING';

    case ERROR = 'ERROR';

    case EXISTING = 'EXISTING';

    case IMPORTED = 'IMPORTED';

    case FAILED = 'FAILED';

    case SKIPPED = 'SKIPPED';

    public function label(): string
    {
        return match ($this) {
            self::VALID =>
                'Valide',

            self::WARNING =>
                'Avertissement',

            self::ERROR =>
                'Erreur',

            self::EXISTING =>
                'Existant',

            self::IMPORTED =>
                'Importé',

            self::FAILED =>
                'Échec',

            self::SKIPPED =>
                'Ignoré',
        };
    }

    public function isImportable(): bool
    {
        return match ($this) {
            self::VALID,
            self::WARNING,
            self::EXISTING =>
                true,

            default =>
                false,
        };
    }

    public function isProcessed(): bool
    {
        return match ($this) {
            self::IMPORTED,
            self::FAILED,
            self::SKIPPED =>
                true,

            default =>
                false,
        };
    }
}