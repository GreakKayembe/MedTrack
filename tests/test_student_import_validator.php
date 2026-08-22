<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use MedTrack\Core\Database\Database;
use MedTrack\Modules\Academic\Services\StudentImport\StudentImportParser;
use MedTrack\Modules\Academic\Services\StudentImport\StudentImportReferenceResolver;
use MedTrack\Modules\Academic\Services\StudentImport\StudentImportValidator;


/*
|--------------------------------------------------------------------------
| Environment
|--------------------------------------------------------------------------
*/

$rootPath =
    dirname(__DIR__);

$dotenv =
    Dotenv::createImmutable(
        $rootPath
    );

$dotenv->safeLoad();


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
| Student Import
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


/*
|--------------------------------------------------------------------------
| Arguments
|--------------------------------------------------------------------------
*/

$filePath =
    $argv[1]
    ?? (
        $rootPath
        . '/tests/fixtures/'
        . 'medtrack_student_import_template.xlsx'
    );

$universityId =
    isset($argv[2])
        ? (int) $argv[2]
        : 0;


if ($universityId <= 0) {
    fwrite(
        STDERR,
        PHP_EOL
        . "Usage :"
        . PHP_EOL
        . PHP_EOL
        . "php tests/test_student_import_validator.php "
        . "<fichier.xlsx> <university_id>"
        . PHP_EOL
        . PHP_EOL
    );

    exit(1);
}


/*
|--------------------------------------------------------------------------
| Test
|--------------------------------------------------------------------------
*/

try {

    echo PHP_EOL;

    echo 'Connexion base de données : OK'
        . PHP_EOL;

    echo 'Université ID : '
        . $universityId
        . PHP_EOL;

    echo 'Fichier : '
        . $filePath
        . PHP_EOL;


    /*
    |--------------------------------------------------------------------------
    | Parse
    |--------------------------------------------------------------------------
    */

    $rows =
        $parser->parse(
            $filePath
        );

    echo PHP_EOL;

    echo 'Parser : OK'
        . PHP_EOL;

    echo 'Lignes Excel détectées : '
        . count($rows)
        . PHP_EOL;

    echo PHP_EOL;

    echo str_repeat(
        '=',
        72
    );

    echo PHP_EOL;


    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    $statistics = [
        'VALID' =>
            0,

        'WARNING' =>
            0,

        'ERROR' =>
            0,

        'EXISTING' =>
            0,
    ];


    /*
    |--------------------------------------------------------------------------
    | Validate rows
    |--------------------------------------------------------------------------
    */

    foreach (
        $rows
        as $row
    ) {

        $result =
            $validator->validate(
                $row,
                $universityId
            );


        if (
            array_key_exists(
                $result->status,
                $statistics
            )
        ) {
            $statistics[
                $result->status
            ]++;
        }


        echo PHP_EOL;

        echo sprintf(
            'Ligne %d — %s %s',
            $row->rowNumber,
            $row->firstName,
            $row->lastName
        );

        echo PHP_EOL;


        echo 'Email : '
            . $row->email
            . PHP_EOL;


        echo 'Matricule : '
            . $row->registrationNumber
            . PHP_EOL;


        echo 'Statut : '
            . $result->status
            . PHP_EOL;


        echo 'Type de doublon : '
            . $result->duplicateType
            . PHP_EOL;


        /*
        |--------------------------------------------------------------------------
        | References
        |--------------------------------------------------------------------------
        */

        echo PHP_EOL;

        echo 'Références résolues :'
            . PHP_EOL;

        echo '  Programme ID : '
            . (
                $result->academicProgramId
                ?? 'NULL'
            )
            . PHP_EOL;

        echo '  Année académique ID : '
            . (
                $result->academicYearId
                ?? 'NULL'
            )
            . PHP_EOL;

        echo '  Niveau ID : '
            . (
                $result->studyLevelId
                ?? 'NULL'
            )
            . PHP_EOL;

        echo '  Cohorte ID : '
            . (
                $result->cohortId
                ?? 'NULL'
            )
            . PHP_EOL;


        /*
        |--------------------------------------------------------------------------
        | Existing entities
        |--------------------------------------------------------------------------
        */

        echo PHP_EOL;

        echo 'Correspondances :'
            . PHP_EOL;

        echo '  User ID : '
            . (
                $result->matchedUserId
                ?? 'NULL'
            )
            . PHP_EOL;

        echo '  Student ID : '
            . (
                $result->matchedStudentId
                ?? 'NULL'
            )
            . PHP_EOL;

        echo '  Enrollment ID : '
            . (
                $result->matchedEnrollmentId
                ?? 'NULL'
            )
            . PHP_EOL;


        /*
        |--------------------------------------------------------------------------
        | Errors
        |--------------------------------------------------------------------------
        */

        if (
            $result->errors !== []
        ) {

            echo PHP_EOL;

            echo 'Erreurs :'
                . PHP_EOL;

            foreach (
                $result->errors
                as $error
            ) {
                echo '  - '
                    . $error
                    . PHP_EOL;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Warnings
        |--------------------------------------------------------------------------
        */

        if (
            $result->warnings !== []
        ) {

            echo PHP_EOL;

            echo 'Avertissements :'
                . PHP_EOL;

            foreach (
                $result->warnings
                as $warning
            ) {
                echo '  - '
                    . $warning
                    . PHP_EOL;
            }
        }


        echo PHP_EOL;

        echo str_repeat(
            '-',
            72
        );

        echo PHP_EOL;
    }


    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    echo PHP_EOL;

    echo 'RÉSUMÉ'
        . PHP_EOL;

    echo str_repeat(
        '=',
        72
    );

    echo PHP_EOL;


    foreach (
        $statistics
        as $status => $count
    ) {
        echo str_pad(
            $status,
            12
        );

        echo ': '
            . $count
            . PHP_EOL;
    }


    echo PHP_EOL;

    echo 'Test terminé.'
        . PHP_EOL;

    echo PHP_EOL;


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
        PHP_EOL
        . 'Fichier : '
        . $exception->getFile()
        . PHP_EOL
    );

    fwrite(
        STDERR,
        'Ligne : '
        . $exception->getLine()
        . PHP_EOL
        . PHP_EOL
    );

    exit(1);
}