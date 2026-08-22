<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Services\StudentImport;

use MedTrack\Modules\Academic\DTO\StudentImport\StudentImportRow;
use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RuntimeException;
use Throwable;

final class StudentImportParser
{
    /**
     * Colonnes obligatoires du modèle Excel MedTrack.
     *
     * @var list<string>
     */
    private const REQUIRED_HEADERS = [
        'first_name',
        'last_name',
        'email',
        'registration_number',
        'academic_program_code',
        'academic_year_label',
        'study_level_code',
    ];

    /**
     * Colonnes facultatives.
     *
     * @var list<string>
     */
    private const OPTIONAL_HEADERS = [
        'phone',
        'cohort_name',
        'birth_date',
        'gender',
    ];

    /**
     * Extensions acceptées.
     *
     * @var list<string>
     */
    private const ALLOWED_EXTENSIONS = [
        'xlsx',
        'xls',
    ];

    /**
     * Parse un fichier Excel et retourne les lignes normalisées.
     *
     * Aucune écriture en base n'est effectuée ici.
     *
     * @return list<StudentImportRow>
     */
    public function parse(string $filePath): array
    {
        $this->assertReadableFile($filePath);

        $spreadsheet = null;

        try {
            $spreadsheet = IOFactory::load($filePath);

            $worksheet = $this->resolveStudentsWorksheet(
                $spreadsheet
            );

            return $this->parseWorksheet(
                $worksheet
            );
        } catch (InvalidArgumentException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new RuntimeException(
                'Impossible de lire le fichier Excel : '
                . $exception->getMessage(),
                0,
                $exception
            );
        } finally {
            if ($spreadsheet instanceof Spreadsheet) {
                $spreadsheet->disconnectWorksheets();
            }
        }
    }

    /**
     * Cherche prioritairement une feuille "Students".
     *
     * Si elle n'existe pas, utilise la première feuille.
     */
    private function resolveStudentsWorksheet(
        Spreadsheet $spreadsheet
    ): Worksheet {
        foreach (
            $spreadsheet->getWorksheetIterator()
            as $worksheet
        ) {
            $title = mb_strtolower(
                trim($worksheet->getTitle()),
                'UTF-8'
            );

            if ($title === 'students') {
                return $worksheet;
            }
        }

        if ($spreadsheet->getSheetCount() < 1) {
            throw new InvalidArgumentException(
                'Le fichier Excel ne contient aucune feuille.'
            );
        }

        return $spreadsheet->getSheet(0);
    }

    /**
     * @return list<StudentImportRow>
     */
    private function parseWorksheet(
        Worksheet $worksheet
    ): array {
        $highestRow = (int) $worksheet
            ->getHighestDataRow();

        if ($highestRow < 1) {
            throw new InvalidArgumentException(
                'Le fichier Excel est vide.'
            );
        }

        $highestColumn = $worksheet
            ->getHighestDataColumn();

        $highestColumnIndex =
            Coordinate::columnIndexFromString(
                $highestColumn
            );

        $headerRow = $this->readRow(
            $worksheet,
            1,
            $highestColumnIndex
        );

        $headerMap = $this->buildHeaderMap(
            $headerRow
        );

        $this->validateHeaders(
            $headerMap
        );

        $rows = [];

        for (
            $rowNumber = 2;
            $rowNumber <= $highestRow;
            $rowNumber++
        ) {
            $rawRow = $this->readRow(
                $worksheet,
                $rowNumber,
                $highestColumnIndex
            );

            if ($this->isEmptyRow($rawRow)) {
                continue;
            }

            $rows[] = $this->mapRow(
                $worksheet,
                $rowNumber,
                $rawRow,
                $headerMap
            );
        }

        if ($rows === []) {
            throw new InvalidArgumentException(
                'Le fichier Excel ne contient aucun étudiant.'
            );
        }

        return $rows;
    }

    /**
     * Lit une ligne Excel.
     *
     * @return array<int, mixed>
     */
    private function readRow(
        Worksheet $worksheet,
        int $rowNumber,
        int $highestColumnIndex
    ): array {
        $row = [];

        for (
            $columnIndex = 1;
            $columnIndex <= $highestColumnIndex;
            $columnIndex++
        ) {
            $row[$columnIndex - 1] = $worksheet
                ->getCell([
                    $columnIndex,
                    $rowNumber,
                ])
                ->getValue();
        }

        return $row;
    }

    /**
     * Construit :
     *
     * [
     *     'first_name' => 0,
     *     'last_name'  => 1,
     *     ...
     * ]
     *
     * @param array<int, mixed> $headerRow
     *
     * @return array<string, int>
     */
    private function buildHeaderMap(
        array $headerRow
    ): array {
        $map = [];

        foreach (
            $headerRow
            as $columnIndex => $value
        ) {
            $header = $this->normalizeHeader(
                $value
            );

            if ($header === '') {
                continue;
            }

            if (array_key_exists($header, $map)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'La colonne "%s" est présente plusieurs fois.',
                        $header
                    )
                );
            }

            $map[$header] = (int) $columnIndex;
        }

        return $map;
    }

    /**
     * @param array<string, int> $headerMap
     */
    private function validateHeaders(
        array $headerMap
    ): void {
        $missing = [];

        foreach (
            self::REQUIRED_HEADERS
            as $requiredHeader
        ) {
            if (
                !array_key_exists(
                    $requiredHeader,
                    $headerMap
                )
            ) {
                $missing[] = $requiredHeader;
            }
        }

        if ($missing !== []) {
            throw new InvalidArgumentException(
                'Colonnes obligatoires manquantes : '
                . implode(', ', $missing)
                . '.'
            );
        }

        $allowedHeaders = array_merge(
            self::REQUIRED_HEADERS,
            self::OPTIONAL_HEADERS
        );

        $unknownHeaders = array_values(
            array_diff(
                array_keys($headerMap),
                $allowedHeaders
            )
        );

        if ($unknownHeaders !== []) {
            throw new InvalidArgumentException(
                'Colonnes non reconnues : '
                . implode(', ', $unknownHeaders)
                . '.'
            );
        }
    }

    /**
     * Transforme une ligne Excel en DTO.
     *
     * @param array<int, mixed> $rawRow
     * @param array<string, int> $headerMap
     */
    private function mapRow(
        Worksheet $worksheet,
        int $rowNumber,
        array $rawRow,
        array $headerMap
    ): StudentImportRow {
        return new StudentImportRow(
            rowNumber: $rowNumber,

            firstName: $this->stringValue(
                $this->value(
                    $rawRow,
                    $headerMap,
                    'first_name'
                )
            ),

            lastName: $this->stringValue(
                $this->value(
                    $rawRow,
                    $headerMap,
                    'last_name'
                )
            ),

            email: $this->normalizeEmail(
                $this->value(
                    $rawRow,
                    $headerMap,
                    'email'
                )
            ),

            phone: $this->normalizePhone(
                $this->value(
                    $rawRow,
                    $headerMap,
                    'phone'
                )
            ),

            registrationNumber: $this->normalizeCode(
                $this->value(
                    $rawRow,
                    $headerMap,
                    'registration_number'
                )
            ),

            academicProgramCode: $this->normalizeCode(
                $this->value(
                    $rawRow,
                    $headerMap,
                    'academic_program_code'
                )
            ),

            academicYearLabel: $this->stringValue(
                $this->value(
                    $rawRow,
                    $headerMap,
                    'academic_year_label'
                )
            ),

            studyLevelCode: $this->normalizeCode(
                $this->value(
                    $rawRow,
                    $headerMap,
                    'study_level_code'
                )
            ),

            cohortName: $this->nullableStringValue(
                $this->value(
                    $rawRow,
                    $headerMap,
                    'cohort_name'
                )
            ),

            birthDate: $this->parseBirthDate(
                $worksheet,
                $rowNumber,
                $headerMap
            ),

            gender: $this->normalizeGender(
                $this->value(
                    $rawRow,
                    $headerMap,
                    'gender'
                )
            ),
        );
    }

    /**
     * @param array<int, mixed> $rawRow
     * @param array<string, int> $headerMap
     */
    private function value(
        array $rawRow,
        array $headerMap,
        string $header
    ): mixed {
        if (!array_key_exists($header, $headerMap)) {
            return null;
        }

        $columnIndex = $headerMap[$header];

        return $rawRow[$columnIndex] ?? null;
    }

    private function stringValue(
        mixed $value
    ): string {
        if ($value === null) {
            return '';
        }

        return trim(
            (string) $value
        );
    }

    private function nullableStringValue(
        mixed $value
    ): ?string {
        $normalized = $this->stringValue(
            $value
        );

        return $normalized !== ''
            ? $normalized
            : null;
    }

    private function normalizeEmail(
        mixed $value
    ): string {
        return mb_strtolower(
            $this->stringValue($value),
            'UTF-8'
        );
    }

    private function normalizePhone(
        mixed $value
    ): ?string {
        $phone = $this->nullableStringValue(
            $value
        );

        if ($phone === null) {
            return null;
        }

        /*
         * On ne valide pas encore le téléphone ici.
         *
         * Le parser se contente de retirer les espaces
         * superflus. La validation métier viendra ensuite.
         */
        $phone = preg_replace(
            '/\s+/',
            '',
            $phone
        ) ?? $phone;

        return $phone !== ''
            ? $phone
            : null;
    }

    private function normalizeCode(
        mixed $value
    ): string {
        return mb_strtoupper(
            $this->stringValue($value),
            'UTF-8'
        );
    }

    private function normalizeGender(
        mixed $value
    ): ?string {
        $gender = $this->nullableStringValue(
            $value
        );

        if ($gender === null) {
            return null;
        }

        return mb_strtoupper(
            $gender,
            'UTF-8'
        );
    }

    /**
     * Les dates Excel peuvent arriver soit :
     *
     * - sous forme de numéro Excel ;
     * - sous forme de texte YYYY-MM-DD.
     *
     * La validation stricte sera effectuée dans le Validator.
     *
     * @param array<string, int> $headerMap
     */
    private function parseBirthDate(
        Worksheet $worksheet,
        int $rowNumber,
        array $headerMap
    ): ?string {
        if (
            !array_key_exists(
                'birth_date',
                $headerMap
            )
        ) {
            return null;
        }

        $zeroBasedColumnIndex =
            $headerMap['birth_date'];

        $excelColumnIndex =
            $zeroBasedColumnIndex + 1;

        $cell = $worksheet->getCell([
            $excelColumnIndex,
            $rowNumber,
        ]);

        $value = $cell->getValue();

        if ($value === null || $value === '') {
            return null;
        }

        if (
            is_numeric($value)
            && ExcelDate::isDateTime($cell)
        ) {
            try {
                return ExcelDate::excelToDateTimeObject(
                    (float) $value
                )->format('Y-m-d');
            } catch (Throwable) {
                return $this->stringValue(
                    $value
                );
            }
        }

        return $this->stringValue(
            $value
        );
    }

    private function normalizeHeader(
        mixed $value
    ): string {
        $header = mb_strtolower(
            trim(
                (string) ($value ?? '')
            ),
            'UTF-8'
        );

        $header = str_replace(
            [
                ' ',
                '-',
            ],
            '_',
            $header
        );

        $header = preg_replace(
            '/_+/',
            '_',
            $header
        ) ?? $header;

        return trim(
            $header,
            '_'
        );
    }

    /**
     * @param array<int, mixed> $rawRow
     */
    private function isEmptyRow(
        array $rawRow
    ): bool {
        foreach ($rawRow as $value) {
            if (
                trim(
                    (string) ($value ?? '')
                ) !== ''
            ) {
                return false;
            }
        }

        return true;
    }

    private function assertReadableFile(
        string $filePath
    ): void {
        if ($filePath === '') {
            throw new InvalidArgumentException(
                'Aucun fichier d’import n’a été fourni.'
            );
        }

        if (!is_file($filePath)) {
            throw new InvalidArgumentException(
                'Le fichier d’import est introuvable.'
            );
        }

        if (!is_readable($filePath)) {
            throw new InvalidArgumentException(
                'Le fichier d’import n’est pas lisible.'
            );
        }

        $extension = mb_strtolower(
            pathinfo(
                $filePath,
                PATHINFO_EXTENSION
            ),
            'UTF-8'
        );

        if (
            !in_array(
                $extension,
                self::ALLOWED_EXTENSIONS,
                true
            )
        ) {
            throw new InvalidArgumentException(
                'Format de fichier non supporté. '
                . 'Utilisez un fichier .xlsx ou .xls.'
            );
        }
    }
}
