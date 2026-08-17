<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

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
        return $this->view->render(
            'academic.enrollments.index',
            [
                'pageTitle' =>
                    'Inscriptions académiques',

                'enrollments' =>
                    $this->academicEnrollmentService
                        ->all(),
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
        $studentId =
            (int) $request->query(
                'student_id',
                0
            );

        $enrollment = [];

        if ($studentId > 0) {
            $enrollment['student_id'] =
                $studentId;
        }

        return $this->view->render(
            'academic.enrollments.create',
            $this->formData(
                enrollment: $enrollment,
                pageTitle: 'Nouvelle inscription académique'
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
            $id =
                $this->academicEnrollmentService
                    ->create(
                        $this->enrollmentPayload(
                            $request
                        )
                    );

            Response::json(
                [
                    'status' => 'success',

                    'message' =>
                        'Inscription académique enregistrée avec succès.',

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
                    'status' => 'error',

                    'message' =>
                        $exception->getMessage(),
                ],
                422
            );
        } catch (Throwable $exception) {
            Response::json(
                [
                    'status' => 'error',

                    'message' =>
                        'Une erreur interne est survenue.',

                    'debug' => [
                        'exception' =>
                            $exception::class,

                        'message' =>
                            $exception->getMessage(),

                        'file' =>
                            $exception->getFile(),

                        'line' =>
                            $exception->getLine(),
                    ],
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

        $enrollment =
            $this->academicEnrollmentService
                ->findOrFail(
                    $id
                );

        return $this->view->render(
            'academic.enrollments.show',
            [
                'pageTitle' =>
                    'Inscription académique',

                'enrollment' =>
                    $enrollment,
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

        $enrollment =
            $this->academicEnrollmentService
                ->findOrFail(
                    $id
                );

        return $this->view->render(
            'academic.enrollments.edit',
            $this->formData(
                enrollment: $enrollment,
                pageTitle: 'Modifier l’inscription académique'
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

            $this->academicEnrollmentService
                ->update(
                    $id,
                    $this->enrollmentPayload(
                        $request
                    )
                );

            Response::json(
                [
                    'status' => 'success',

                    'message' =>
                        'Inscription académique mise à jour avec succès.',

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
                    'status' => 'error',

                    'message' =>
                        $exception->getMessage(),
                ],
                422
            );
        } catch (Throwable $exception) {
            Response::json(
                [
                    'status' => 'error',

                    'message' =>
                        'Une erreur interne est survenue.',

                    'debug' => [
                        'exception' =>
                            $exception::class,

                        'message' =>
                            $exception->getMessage(),

                        'file' =>
                            $exception->getFile(),

                        'line' =>
                            $exception->getLine(),
                    ],
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
        array $enrollment,
        string $pageTitle
    ): array {
        return [
            'pageTitle' =>
                $pageTitle,

            'enrollment' =>
                $enrollment,

            'students' =>
                $this->studentService
                    ->all(),

            'universities' =>
                $this->universityService
                    ->all(),

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