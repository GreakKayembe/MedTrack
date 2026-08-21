<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Context\AccessContext;
use MedTrack\Core\Context\AccessContextResolver;
use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Academic\Services\AcademicEnrollmentService;
use MedTrack\Modules\Academic\Services\AcademicProgramService;
use MedTrack\Modules\Academic\Services\AcademicYearService;
use MedTrack\Modules\Academic\Services\CohortService;
use MedTrack\Modules\Academic\Services\StudentService;
use MedTrack\Modules\Academic\Services\StudyLevelService;
use MedTrack\Modules\Academic\Services\UniversityService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

final class AcademicEnrollmentController
{
    public function __construct(
        private readonly AcademicEnrollmentService $academicEnrollmentService,
        private readonly StudentService $studentService,
        private readonly UniversityService $universityService,
        private readonly AcademicProgramService $academicProgramService,
        private readonly AcademicYearService $academicYearService,
        private readonly StudyLevelService $studyLevelService,
        private readonly CohortService $cohortService,
        private readonly AccessContextResolver $accessContextResolver,
        private readonly View $view
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ): string {
        $context =
            $this->accessContext();

        if ($context->isPlatform()) {
            $enrollments =
                $this->academicEnrollmentService
                    ->all();
        } elseif (
            $this->isUniversityContext(
                $context
            )
        ) {
            $enrollments =
                $this->academicEnrollmentService
                    ->allForUniversity(
                        $context->organizationId()
                    );
        } else {
            throw new RuntimeException(
                'Ce contexte ne permet pas '
                . 'de consulter les inscriptions académiques.'
            );
        }

        return $this->view->render(
            'academic.enrollments.index',
            [
                'pageTitle' =>
                    'Inscriptions académiques',

                'enrollments' =>
                    $enrollments,

                'isPlatform' =>
                    $context->isPlatform(),

                'isUniversityContext' =>
                    $this->isUniversityContext(
                        $context
                    ),

                'activeUniversityId' =>
                    $this->activeUniversityId(
                        $context
                    ),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        Request $request
    ): string {
        $context =
            $this->accessContext();

        $this->ensureSupportedContext(
            $context
        );

        $studentId =
            (int) $request->query(
                'student_id',
                0
            );

        $enrollment = [];

        /*
         * PLATFORM peut préremplir librement un étudiant.
         *
         * UNIVERSITY ne peut préremplir qu'un étudiant
         * déjà visible dans son propre périmètre.
         */
        if ($studentId > 0) {
            $student =
                $context->isPlatform()
                    ? $this->studentService
                        ->findById(
                            $studentId
                        )
                    : $this->studentService
                        ->findByIdForUniversity(
                            $studentId,
                            $context->organizationId()
                        );

            if ($student !== null) {
                $enrollment['student_id'] =
                    $studentId;
            }
        }

        return $this->view->render(
            'academic.enrollments.create',
            $this->formData(
                context: $context,
                enrollment: $enrollment,
                pageTitle:
                    'Nouvelle inscription académique'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): never {
        try {
            $context =
                $this->accessContext();

            $this->ensureSupportedContext(
                $context
            );

            $payload =
                $this->enrollmentPayload(
                    $request
                );

            if ($context->isPlatform()) {
                $id =
                    $this->academicEnrollmentService
                        ->create(
                            $payload
                        );
            } else {
                $id =
                    $this->academicEnrollmentService
                        ->createForUniversity(
                            $context->organizationId(),
                            $payload
                        );
            }

            Response::json(
                [
                    'status' =>
                        'success',

                    'message' =>
                        'Inscription académique '
                        . 'enregistrée avec succès.',

                    'id' =>
                        $id,

                    'redirect' =>
                        '/academic-enrollments/'
                        . $id,
                ],
                201
            );
        } catch (
            InvalidArgumentException
            | RuntimeException $exception
        ) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'VALIDATION_ERROR',

                    'message' =>
                        $exception->getMessage(),
                ],
                422
            );
        } catch (Throwable $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'ACADEMIC_ENROLLMENT_CREATION_FAILED',

                    'message' =>
                        'Impossible d’enregistrer '
                        . 'l’inscription académique '
                        . 'pour le moment.',
                ],
                500
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $context =
            $this->accessContext();

        $this->ensureSupportedContext(
            $context
        );

        $enrollment =
            $context->isPlatform()
                ? $this->academicEnrollmentService
                    ->findOrFail(
                        $id
                    )
                : $this->academicEnrollmentService
                    ->findOrFailForUniversity(
                        $id,
                        $context->organizationId()
                    );

        return $this->view->render(
            'academic.enrollments.show',
            [
                'pageTitle' =>
                    'Inscription académique',

                'enrollment' =>
                    $enrollment,

                'isPlatform' =>
                    $context->isPlatform(),

                'isUniversityContext' =>
                    $this->isUniversityContext(
                        $context
                    ),

                'activeUniversityId' =>
                    $this->activeUniversityId(
                        $context
                    ),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $context =
            $this->accessContext();

        $this->ensureSupportedContext(
            $context
        );

        $enrollment =
            $context->isPlatform()
                ? $this->academicEnrollmentService
                    ->findOrFail(
                        $id
                    )
                : $this->academicEnrollmentService
                    ->findOrFailForUniversity(
                        $id,
                        $context->organizationId()
                    );

        return $this->view->render(
            'academic.enrollments.edit',
            $this->formData(
                context: $context,
                enrollment: $enrollment,
                pageTitle:
                    'Modifier l’inscription académique'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request
    ): never {
        try {
            $id =
                $this->routeId(
                    $request
                );

            $context =
                $this->accessContext();

            $this->ensureSupportedContext(
                $context
            );

            $payload =
                $this->enrollmentPayload(
                    $request
                );

            if ($context->isPlatform()) {
                $this->academicEnrollmentService
                    ->update(
                        $id,
                        $payload
                    );
            } else {
                $this->academicEnrollmentService
                    ->updateForUniversity(
                        $id,
                        $context->organizationId(),
                        $payload
                    );
            }

            Response::json(
                [
                    'status' =>
                        'success',

                    'message' =>
                        'Inscription académique '
                        . 'mise à jour avec succès.',

                    'id' =>
                        $id,

                    'redirect' =>
                        '/academic-enrollments/'
                        . $id,
                ]
            );
        } catch (
            InvalidArgumentException
            | RuntimeException $exception
        ) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'VALIDATION_ERROR',

                    'message' =>
                        $exception->getMessage(),
                ],
                422
            );
        } catch (Throwable $exception) {
            Response::json(
                [
                    'status' =>
                        'error',

                    'code' =>
                        'ACADEMIC_ENROLLMENT_UPDATE_FAILED',

                    'message' =>
                        'Impossible de modifier '
                        . 'l’inscription académique '
                        . 'pour le moment.',
                ],
                500
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Form data
    |--------------------------------------------------------------------------
    */

    private function formData(
        AccessContext $context,
        array $enrollment,
        string $pageTitle
    ): array {
        $isPlatform =
            $context->isPlatform();

        $isUniversity =
            $this->isUniversityContext(
                $context
            );

        if (!$isPlatform && !$isUniversity) {
            throw new RuntimeException(
                'Ce contexte ne permet pas '
                . 'de gérer les inscriptions académiques.'
            );
        }

        /*
         * PLATFORM :
         * - tous les étudiants
         * - toutes les universités
         * - tous les programmes
         * - toutes les cohortes
         *
         * UNIVERSITY :
         * - uniquement les étudiants déjà visibles
         *   dans son périmètre
         * - aucune liste d'universités
         * - programmes/cohortes déjà scopés
         *   par leurs services AccessContext-aware
         */
        $students =
            $isPlatform
                ? $this->studentService
                    ->all()
                : $this->studentService
                    ->allForUniversity(
                        $context->organizationId()
                    );

        $universities =
            $isPlatform
                ? $this->universityService
                    ->all()
                : [];

        return [
            'pageTitle' =>
                $pageTitle,

            'enrollment' =>
                $enrollment,

            'students' =>
                $students,

            'universities' =>
                $universities,

            'academicPrograms' =>
                $this->academicProgramService
                    ->all(),

            'academicYears' =>
                $this->academicYearService
                    ->all(),

            'studyLevels' =>
                $this->studyLevelService
                    ->all(),

            'cohorts' =>
                $this->cohortService
                    ->all(),

            'isPlatform' =>
                $isPlatform,

            'isUniversityContext' =>
                $isUniversity,

            'activeUniversityId' =>
                $this->activeUniversityId(
                    $context
                ),

            'pageScripts' => [
                '/assets/js/medtrack-academic-enrollment-form.js',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Request payload
    |--------------------------------------------------------------------------
    */

    private function enrollmentPayload(
        Request $request
    ): array {
        return [
            'student_id' =>
                $request->input(
                    'student_id'
                ),

            'university_id' =>
                $request->input(
                    'university_id'
                ),

            'academic_program_id' =>
                $request->input(
                    'academic_program_id'
                ),

            'academic_year_id' =>
                $request->input(
                    'academic_year_id'
                ),

            'study_level_id' =>
                $request->input(
                    'study_level_id'
                ),

            'cohort_id' =>
                $request->input(
                    'cohort_id'
                ),

            'registration_number' =>
                $request->input(
                    'registration_number'
                ),

            'status' =>
                $request->input(
                    'status'
                ),

            'enrolled_at' =>
                $request->input(
                    'enrolled_at'
                ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Access context
    |--------------------------------------------------------------------------
    */

    private function accessContext(): AccessContext
    {
        return $this->accessContextResolver
            ->resolve();
    }

    private function isUniversityContext(
        AccessContext $context
    ): bool {
        return
            $context->isOrganization()
            && strtoupper(
                trim(
                    $context->organizationType()
                )
            ) === 'UNIVERSITY';
    }

    private function ensureSupportedContext(
        AccessContext $context
    ): void {
        if ($context->isPlatform()) {
            return;
        }

        if (
            $this->isUniversityContext(
                $context
            )
        ) {
            return;
        }

        throw new RuntimeException(
            'Ce contexte ne permet pas '
            . 'de gérer les inscriptions académiques.'
        );
    }

    private function activeUniversityId(
        AccessContext $context
    ): ?int {
        if (
            !$this->isUniversityContext(
                $context
            )
        ) {
            return null;
        }

        return $context->organizationId();
    }


    /*
    |--------------------------------------------------------------------------
    | Route ID
    |--------------------------------------------------------------------------
    */

    private function routeId(
        Request $request
    ): int {
        $id =
            (int) $request->attribute(
                'id',
                0
            );

        if ($id <= 0) {
            throw new InvalidArgumentException(
                'Identifiant de l’inscription académique invalide.'
            );
        }

        return $id;
    }
}