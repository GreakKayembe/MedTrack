<?php

declare(strict_types=1);

namespace MedTrack\Modules\Academic\Controllers;

use MedTrack\Core\Context\AccessContextResolver;
use MedTrack\Core\Http\Request;
use MedTrack\Core\Http\Response;
use MedTrack\Core\Http\View;
use MedTrack\Modules\Academic\Services\AcademicEnrollmentService;
use MedTrack\Modules\Academic\Services\StudentService;
use RuntimeException;
use Throwable;

final class StudentController
{
    public function __construct(
        private readonly StudentService $students,
        private readonly AcademicEnrollmentService $academicEnrollments,
        private readonly AccessContextResolver $accessContextResolver,
        private readonly View $view
    ) {
    }

    /**
     * Affiche la liste des étudiants.
     */
    public function index(
        Request $request
    ): string {
        $context =
            $this->accessContextResolver
                ->resolve();

        if ($context->isPlatform()) {
            $students =
                $this->students->all();
        } elseif (
            $context->isOrganization()
            && $context->organizationType()
                === 'UNIVERSITY'
        ) {
            $students =
                $this->students
                    ->allForUniversity(
                        $context->organizationId()
                    );
        } else {
            throw new RuntimeException(
                'Ce contexte ne permet pas '
                . 'de consulter les étudiants.'
            );
        }

        return $this->view->render(
            'academic.students.index',
            [
                'pageTitle' =>
                    'Étudiants',

                'students' =>
                    $students,

                'isPlatform' =>
                    $context->isPlatform(),

                'isUniversityContext' =>
                    $context->isOrganization()
                    && $context->organizationType()
                        === 'UNIVERSITY',
            ]
        );
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create(
        Request $request
    ): string {
        $this->assertPlatformStudentManagementContext();

        return $this->view->render(
            'academic.students.create',
            [
                'pageTitle' =>
                    'Nouvel étudiant',

                'pageScripts' => [
                    '/assets/js/medtrack-student-form.js',
                ],
            ]
        );
    }

    /**
     * Enregistre un nouvel étudiant.
     *
     * La création de l'étudiant ne constitue pas
     * encore son inscription universitaire.
     *
     * Le rattachement à une université est porté
     * par academic_enrollments.
     */
    public function store(
        Request $request
    ): never {
        try {
            $this->assertPlatformStudentManagementContext();

            $studentId =
                $this->students->create(
                    $this->studentPayload(
                        $request
                    )
                );
        } catch (RuntimeException $exception) {
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
                        'STUDENT_CREATION_FAILED',

                    'message' =>
                        'Impossible d’enregistrer '
                        . 'l’étudiant pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'L’étudiant a été enregistré '
                    . 'avec succès.',

                'student_id' =>
                    $studentId,

                'redirect' =>
                    '/students/' . $studentId,
            ],
            201
        );
    }

    /**
     * Affiche la fiche d'un étudiant.
     */
    public function show(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $context =
            $this->accessContextResolver
                ->resolve();

        /*
        |--------------------------------------------------------------------------
        | Platform
        |--------------------------------------------------------------------------
        */

        if ($context->isPlatform()) {
            $student =
                $this->students
                    ->findById(
                        $id
                    );

            $academicEnrollments =
                $student !== null
                    ? $this->academicEnrollments
                        ->findByStudent(
                            $id
                        )
                    : [];
        }

        /*
        |--------------------------------------------------------------------------
        | University
        |--------------------------------------------------------------------------
        */

        elseif (
            $context->isOrganization()
            && $context->organizationType()
                === 'UNIVERSITY'
        ) {
            $universityId =
                $context->organizationId();

            $student =
                $this->students
                    ->findByIdForUniversity(
                        $id,
                        $universityId
                    );

            $academicEnrollments =
                $student !== null
                    ? $this->academicEnrollments
                        ->findByStudentAndUniversity(
                            $id,
                            $universityId
                        )
                    : [];
        }

        /*
        |--------------------------------------------------------------------------
        | Unsupported context
        |--------------------------------------------------------------------------
        */

        else {
            $student = null;
            $academicEnrollments = [];
        }

        if ($student === null) {
            http_response_code(404);

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Étudiant introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.students.show',
            [
                'pageTitle' =>
                    $this->studentFullName(
                        $student
                    ),

                'student' =>
                    $student,

                'academicEnrollments' =>
                    $academicEnrollments,

                'isPlatform' =>
                    $context->isPlatform(),

                'isUniversityContext' =>
                    $context->isOrganization()
                    && $context->organizationType()
                        === 'UNIVERSITY',
            ]
        );
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(
        Request $request
    ): string {
        $id =
            $this->routeId(
                $request
            );

        $this->assertPlatformStudentManagementContext();

        $student =
            $this->students->findById(
                $id
            );

        if ($student === null) {
            http_response_code(404);

            return $this->view->render(
                'errors.404',
                [
                    'pageTitle' =>
                        'Étudiant introuvable',
                ]
            );
        }

        return $this->view->render(
            'academic.students.edit',
            [
                'pageTitle' =>
                    'Modifier l’étudiant',

                'student' =>
                    $student,

                'pageScripts' => [
                    '/assets/js/medtrack-student-form.js',
                ],
            ]
        );
    }

    /**
     * Met à jour un étudiant.
     */
    public function update(
        Request $request
    ): never {
        $id =
            $this->routeId(
                $request
            );

        try {
            $this->assertPlatformStudentManagementContext();

            $this->students->update(
                $id,
                $this->studentPayload(
                    $request
                )
            );
        } catch (RuntimeException $exception) {
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
                        'STUDENT_UPDATE_FAILED',

                    'message' =>
                        'Impossible de modifier '
                        . 'l’étudiant pour le moment.',
                ],
                500
            );
        }

        Response::json(
            [
                'status' =>
                    'success',

                'message' =>
                    'L’étudiant a été mis à jour '
                    . 'avec succès.',

                'redirect' =>
                    '/students/' . $id,
            ]
        );
    }

    /**
     * Vérifie que le contexte actif est PLATFORM.
     *
     * L'identité globale d'un étudiant ne peut être
     * créée ou modifiée que depuis l'espace plateforme.
     * Les universités administrent uniquement leurs
     * inscriptions via academic_enrollments.
     */
    private function assertPlatformStudentManagementContext(): void
    {
        $context =
            $this->accessContextResolver
                ->resolve();

        if ($context->isPlatform()) {
            return;
        }

        throw new RuntimeException(
            'Seul l’espace plateforme peut '
            . 'administrer l’identité globale '
            . 'des étudiants.'
        );
    }

    /**
     * Construit les données étudiant provenant
     * de la requête HTTP.
     *
     * UUID, created_at et updated_at ne sont
     * jamais acceptés depuis le formulaire.
     */
    private function studentPayload(
        Request $request
    ): array {
        return [
            'user_id' =>
                $request->input(
                    'user_id',
                    ''
                ),

            'national_student_number' =>
                $request->input(
                    'national_student_number',
                    ''
                ),

            'first_name' =>
                $request->input(
                    'first_name',
                    ''
                ),

            'middle_name' =>
                $request->input(
                    'middle_name',
                    ''
                ),

            'last_name' =>
                $request->input(
                    'last_name',
                    ''
                ),

            'gender' =>
                $request->input(
                    'gender',
                    ''
                ),

            'birth_date' =>
                $request->input(
                    'birth_date',
                    ''
                ),

            'birth_place' =>
                $request->input(
                    'birth_place',
                    ''
                ),

            'nationality' =>
                $request->input(
                    'nationality',
                    ''
                ),

            'email' =>
                $request->input(
                    'email',
                    ''
                ),

            'phone' =>
                $request->input(
                    'phone',
                    ''
                ),

            'status' =>
                $request->input(
                    'status',
                    'ACTIVE'
                ),
        ];
    }

    /**
     * Construit le nom complet destiné
     * à l'affichage.
     */
    private function studentFullName(
        array $student
    ): string {
        $parts = [
            trim(
                (string) (
                    $student['first_name']
                    ?? ''
                )
            ),

            trim(
                (string) (
                    $student['middle_name']
                    ?? ''
                )
            ),

            trim(
                (string) (
                    $student['last_name']
                    ?? ''
                )
            ),
        ];

        $parts =
            array_values(
                array_filter(
                    $parts,
                    static fn (
                        string $part
                    ): bool => $part !== ''
                )
            );

        return $parts !== []
            ? implode(
                ' ',
                $parts
            )
            : 'Étudiant';
    }

    /**
     * Extrait l'identifiant numérique
     * fourni par le Router.
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
            throw new RuntimeException(
                'Identifiant d’étudiant invalide.'
            );
        }

        return $id;
    }
}