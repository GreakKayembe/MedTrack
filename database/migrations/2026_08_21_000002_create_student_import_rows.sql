CREATE TABLE student_import_rows (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

    student_import_id BIGINT UNSIGNED NOT NULL,

    source_row_number INT UNSIGNED NOT NULL,

    status ENUM(
        'VALID',
        'WARNING',
        'ERROR',
        'EXISTING',
        'IMPORTED',
        'FAILED',
        'SKIPPED'
    )
        COLLATE utf8mb4_unicode_ci
        NOT NULL,

    duplicate_type ENUM(
        'NONE',
        'SAME_UNIVERSITY_REGISTRATION',
        'EXISTING_USER',
        'EXISTING_STUDENT',
        'EXISTING_ENROLLMENT'
    )
        COLLATE utf8mb4_unicode_ci
        NOT NULL
        DEFAULT 'NONE',


    /*
    |--------------------------------------------------------------------------
    | Données normalisées
    |--------------------------------------------------------------------------
    */

    first_name VARCHAR(150)
        COLLATE utf8mb4_unicode_ci
        DEFAULT NULL,

    last_name VARCHAR(150)
        COLLATE utf8mb4_unicode_ci
        DEFAULT NULL,

    email VARCHAR(190)
        COLLATE utf8mb4_unicode_ci
        DEFAULT NULL,

    phone VARCHAR(30)
        COLLATE utf8mb4_unicode_ci
        DEFAULT NULL,

    registration_number VARCHAR(80)
        COLLATE utf8mb4_unicode_ci
        DEFAULT NULL,

    academic_program_code VARCHAR(50)
        COLLATE utf8mb4_unicode_ci
        DEFAULT NULL,

    academic_year_label VARCHAR(50)
        COLLATE utf8mb4_unicode_ci
        DEFAULT NULL,

    study_level_code VARCHAR(50)
        COLLATE utf8mb4_unicode_ci
        DEFAULT NULL,

    cohort_name VARCHAR(100)
        COLLATE utf8mb4_unicode_ci
        DEFAULT NULL,

    birth_date DATE DEFAULT NULL,

    gender VARCHAR(30)
        COLLATE utf8mb4_unicode_ci
        DEFAULT NULL,


    /*
    |--------------------------------------------------------------------------
    | Source
    |--------------------------------------------------------------------------
    */

    raw_data JSON DEFAULT NULL,

    normalized_data JSON DEFAULT NULL,


    /*
    |--------------------------------------------------------------------------
    | Références résolues
    |--------------------------------------------------------------------------
    */

    resolved_academic_program_id
        BIGINT UNSIGNED
        DEFAULT NULL,

    resolved_academic_year_id
        BIGINT UNSIGNED
        DEFAULT NULL,

    resolved_study_level_id
        BIGINT UNSIGNED
        DEFAULT NULL,

    resolved_cohort_id
        BIGINT UNSIGNED
        DEFAULT NULL,


    /*
    |--------------------------------------------------------------------------
    | Correspondances détectées
    |--------------------------------------------------------------------------
    */

    matched_user_id
        BIGINT UNSIGNED
        DEFAULT NULL,

    matched_student_id
        BIGINT UNSIGNED
        DEFAULT NULL,

    matched_enrollment_id
        BIGINT UNSIGNED
        DEFAULT NULL,


    /*
    |--------------------------------------------------------------------------
    | Entités créées
    |--------------------------------------------------------------------------
    */

    created_user_id
        BIGINT UNSIGNED
        DEFAULT NULL,

    created_student_id
        BIGINT UNSIGNED
        DEFAULT NULL,

    created_enrollment_id
        BIGINT UNSIGNED
        DEFAULT NULL,


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    errors_json JSON DEFAULT NULL,

    warnings_json JSON DEFAULT NULL,

    processed_at TIMESTAMP NULL DEFAULT NULL,

    created_at TIMESTAMP
        NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP
        NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,


    /*
    |--------------------------------------------------------------------------
    | Clés
    |--------------------------------------------------------------------------
    */

    PRIMARY KEY (id),

    UNIQUE KEY uk_student_import_row (
        student_import_id,
        source_row_number
    ),

    KEY idx_student_import_row_status (
        student_import_id,
        status
    ),

    KEY idx_student_import_registration (
        registration_number
    ),

    KEY idx_student_import_email (
        email
    ),

    KEY idx_student_import_program (
        resolved_academic_program_id
    ),

    KEY idx_student_import_year (
        resolved_academic_year_id
    ),

    KEY idx_student_import_level (
        resolved_study_level_id
    ),

    KEY idx_student_import_cohort (
        resolved_cohort_id
    ),

    KEY idx_student_import_matched_user (
        matched_user_id
    ),

    KEY idx_student_import_matched_student (
        matched_student_id
    ),

    KEY idx_student_import_matched_enrollment (
        matched_enrollment_id
    ),

    KEY idx_student_import_created_user (
        created_user_id
    ),

    KEY idx_student_import_created_student (
        created_student_id
    ),

    KEY idx_student_import_created_enrollment (
        created_enrollment_id
    ),


    /*
    |--------------------------------------------------------------------------
    | Foreign keys
    |--------------------------------------------------------------------------
    */

    CONSTRAINT fk_student_import_row_import
        FOREIGN KEY (
            student_import_id
        )
        REFERENCES student_imports (id)
        ON DELETE CASCADE,

    CONSTRAINT fk_student_import_row_program
        FOREIGN KEY (
            resolved_academic_program_id
        )
        REFERENCES academic_programs (id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_student_import_row_year
        FOREIGN KEY (
            resolved_academic_year_id
        )
        REFERENCES academic_years (id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_student_import_row_level
        FOREIGN KEY (
            resolved_study_level_id
        )
        REFERENCES study_levels (id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_student_import_row_cohort
        FOREIGN KEY (
            resolved_cohort_id
        )
        REFERENCES cohorts (id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_student_import_row_matched_user
        FOREIGN KEY (
            matched_user_id
        )
        REFERENCES users (id)
        ON DELETE SET NULL,

    CONSTRAINT fk_student_import_row_matched_student
        FOREIGN KEY (
            matched_student_id
        )
        REFERENCES students (id)
        ON DELETE SET NULL,

    CONSTRAINT fk_student_import_row_matched_enrollment
        FOREIGN KEY (
            matched_enrollment_id
        )
        REFERENCES academic_enrollments (id)
        ON DELETE SET NULL,

    CONSTRAINT fk_student_import_row_created_user
        FOREIGN KEY (
            created_user_id
        )
        REFERENCES users (id)
        ON DELETE SET NULL,

    CONSTRAINT fk_student_import_row_created_student
        FOREIGN KEY (
            created_student_id
        )
        REFERENCES students (id)
        ON DELETE SET NULL,

    CONSTRAINT fk_student_import_row_created_enrollment
        FOREIGN KEY (
            created_enrollment_id
        )
        REFERENCES academic_enrollments (id)
        ON DELETE SET NULL

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;