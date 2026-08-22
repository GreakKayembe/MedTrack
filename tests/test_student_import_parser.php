<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use MedTrack\Modules\Academic\Services\StudentImport\StudentImportParser;

if ($argc < 2) {
    fwrite(
        STDERR,
        "Usage: php tests/test_student_import_parser.php chemin/fichier.xlsx\n"
    );

    exit(1);
}

$filePath =
    (string) $argv[1];

$parser =
    new StudentImportParser();

try {
    $rows =
        $parser->parse(
            $filePath
        );

    echo PHP_EOL;
    echo 'Lignes lues : '
        . count($rows)
        . PHP_EOL;
    echo PHP_EOL;

    foreach ($rows as $row) {
        print_r(
            $row->toArray()
        );

        echo PHP_EOL;
    }

    exit(0);

} catch (Throwable $exception) {
    fwrite(
        STDERR,
        'ERREUR : '
        . $exception->getMessage()
        . PHP_EOL
    );

    exit(1);
}