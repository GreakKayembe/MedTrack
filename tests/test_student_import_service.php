<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use MedTrack\Core\Database\Database;
use MedTrack\Modules\Academic\Repositories\StudentImport\StudentImportRepository;
use MedTrack\Modules\Academic\Repositories\StudentImport\StudentImportRowRepository;
use MedTrack\Modules\Academic\Services\StudentImport\StudentImportParser;
use MedTrack\Modules\Academic\Services\StudentImport\StudentImportReferenceResolver;
use MedTrack\Modules\Academic\Services\StudentImport\StudentImportService;
use MedTrack\Modules\Academic\Services\StudentImport\StudentImportValidator;


/*
|--------------------------------------------------------------------------
| Environment
|--------------------------------------------------------------------------
*/

$rootPath =
    dirname(__DIR__);

Dotenv::createImmutable(
    $rootPath
)->safeLoad();


/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/

$config =
    require $rootPath
    . '/config/database.php';

$database =
    new Database(
        $config
    );

$pdo =
    $database->connection();


/*
|--------------------------------------------------------------------------
| Dependencies
|--------------------------------------------------------------------------
*/

$parser =
    new StudentImportParser();

$resolver =
    new StudentImportReferenceResolver(
        $pdo
    );

$validator =
    new StudentImportValidator(
        $resolver
    );

$importRepository =
    new StudentImportRepository(
        $pdo
    );

$rowRepository =
    new StudentImportRowRepository(
        $pdo
    );

$service =
    new StudentImportService(
        $pdo,
        $parser,
        $validator,
        $importRepository,
        $rowRepository
    );


/*
|--------------------------------------------------------------------------
| Test parameters
|--------------------------------------------------------------------------
*/

$filePath =
    $rootPath
    . '/tests/fixtures/'
    . 'medtrack_student_import_template_valid_university_1.xlsx';

$universityId =
    1;

$uploadedByUserId =
    4;


/*
|--------------------------------------------------------------------------
| Execute
|--------------------------------------------------------------------------
*/

try {
    echo PHP_EOL;
    echo "TEST STUDENT IMPORT SERVICE"
        . PHP_EOL;

    echo str_repeat(
        '=',
        72
    )
        . PHP_EOL;

    echo 'Université ID : '
        . $universityId
        . PHP_EOL;

    echo 'Utilisateur ID : '
        . $uploadedByUserId
        . PHP_EOL;

    echo 'Fichier : '
        . $filePath
        . PHP_EOL;

    echo PHP_EOL;


    /*
    |--------------------------------------------------------------------------
    | Prepare import
    |--------------------------------------------------------------------------
    */

    $result =
        $service->prepare(
            $filePath,
            $universityId,
            $uploadedByUserId
        );


    echo 'Import créé avec succès.'
        . PHP_EOL;

    echo PHP_EOL;

    print_r(
        $result
    );


    /*
    |--------------------------------------------------------------------------
    | Preview
    |--------------------------------------------------------------------------
    */

    $preview =
        $service->preview(
            (int) $result['import_id'],
            $universityId
        );

    if ($preview === null) {
        throw new RuntimeException(
            'La prévisualisation est introuvable.'
        );
    }


    echo PHP_EOL;

    echo 'Import :'
        . PHP_EOL;

    print_r(
        $preview['import']
    );


    echo PHP_EOL;

    echo 'Lignes :'
        . PHP_EOL;

    foreach (
        $preview['rows']
        as $row
    ) {
        print_r(
            $row
        );

        echo PHP_EOL;
    }


    echo PHP_EOL;

    echo 'TEST TERMINÉ'
        . PHP_EOL;

    exit(0);

} catch (Throwable $exception) {
    fwrite(
        STDERR,
        PHP_EOL
        . 'ERREUR : '
        . $exception->getMessage()
        . PHP_EOL
    );

    fwrite(
        STDERR,
        'Fichier : '
        . $exception->getFile()
        . PHP_EOL
    );

    fwrite(
        STDERR,
        'Ligne : '
        . $exception->getLine()
        . PHP_EOL
    );

    exit(1);
}