<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Enums;

enum StudentImportStatus: string
{
    case UPLOADED = 'UPLOADED';

    case VALIDATING = 'VALIDATING';

    case READY = 'READY';

    case PROCESSING = 'PROCESSING';

    case COMPLETED = 'COMPLETED';

    case COMPLETED_WITH_ERRORS = 'COMPLETED_WITH_ERRORS';

    case FAILED = 'FAILED';

    case CANCELLED = 'CANCELLED';

    public function label(): string
    {
        return match ($this) {
            self::UPLOADED =>
                'Fichier chargé',

            self::VALIDATING =>
                'Validation en cours',

            self::READY =>
                'Prêt à importer',

            self::PROCESSING =>
                'Importation en cours',

            self::COMPLETED =>
                'Importation terminée',

            self::COMPLETED_WITH_ERRORS =>
                'Terminée avec erreurs',

            self::FAILED =>
                'Échec',

            self::CANCELLED =>
                'Annulée',
        };
    }

    public function isTerminal(): bool
    {
        return match ($this) {
            self::COMPLETED,
            self::COMPLETED_WITH_ERRORS,
            self::FAILED,
            self::CANCELLED =>
                true,

            default =>
                false,
        };
    }

    public function canBeConfirmed(): bool
    {
        return $this === self::READY;
    }
}