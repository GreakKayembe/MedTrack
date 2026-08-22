CREATE TABLE student_imports (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

    uuid CHAR(36)
        COLLATE utf8mb4_unicode_ci
        NOT NULL,

    university_id BIGINT UNSIGNED NOT NULL,

    uploaded_by_user_id BIGINT UNSIGNED NOT NULL,

    confirmed_by_user_id BIGINT UNSIGNED DEFAULT NULL,

    original_filename VARCHAR(255)
        COLLATE utf8mb4_unicode_ci
        NOT NULL,

    stored_filename VARCHAR(255)
        COLLATE utf8mb4_unicode_ci
        DEFAULT NULL,

    storage_path VARCHAR(500)
        COLLATE utf8mb4_unicode_ci
        DEFAULT NULL,

    mime_type VARCHAR(120)
        COLLATE utf8mb4_unicode_ci
        DEFAULT NULL,

    file_size BIGINT UNSIGNED DEFAULT NULL,

    file_sha256 CHAR(64)
        COLLATE utf8mb4_unicode_ci
        DEFAULT NULL,

    template_version VARCHAR(30)
        COLLATE utf8mb4_unicode_ci
        NOT NULL
        DEFAULT '1.0',

    status ENUM(
        'UPLOADED',
        'VALIDATING',
        'READY',
        'PROCESSING',
        'COMPLETED',
        'COMPLETED_WITH_ERRORS',
        'FAILED',
        'CANCELLED'
    )
        COLLATE utf8mb4_unicode_ci
        NOT NULL
        DEFAULT 'UPLOADED',

    total_rows INT UNSIGNED NOT NULL DEFAULT 0,
    valid_rows INT UNSIGNED NOT NULL DEFAULT 0,
    warning_rows INT UNSIGNED NOT NULL DEFAULT 0,
    error_rows INT UNSIGNED NOT NULL DEFAULT 0,
    existing_rows INT UNSIGNED NOT NULL DEFAULT 0,

    imported_rows INT UNSIGNED NOT NULL DEFAULT 0,
    failed_rows INT UNSIGNED NOT NULL DEFAULT 0,
    skipped_rows INT UNSIGNED NOT NULL DEFAULT 0,

    created_users INT UNSIGNED NOT NULL DEFAULT 0,
    created_students INT UNSIGNED NOT NULL DEFAULT 0,
    created_enrollments INT UNSIGNED NOT NULL DEFAULT 0,

    validated_at TIMESTAMP NULL DEFAULT NULL,
    confirmed_at TIMESTAMP NULL DEFAULT NULL,
    processing_started_at TIMESTAMP NULL DEFAULT NULL,
    completed_at TIMESTAMP NULL DEFAULT NULL,

    created_at TIMESTAMP
        NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP
        NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),

    UNIQUE KEY uk_student_import_uuid (
        uuid
    ),

    KEY idx_student_import_university (
        university_id
    ),

    KEY idx_student_import_uploader (
        uploaded_by_user_id
    ),

    KEY idx_student_import_confirmer (
        confirmed_by_user_id
    ),

    KEY idx_student_import_status (
        status
    ),

    KEY idx_student_import_hash (
        file_sha256
    ),

    KEY idx_student_import_university_status (
        university_id,
        status
    ),

    CONSTRAINT fk_student_import_university
        FOREIGN KEY (university_id)
        REFERENCES universities (organization_id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_student_import_uploader
        FOREIGN KEY (uploaded_by_user_id)
        REFERENCES users (id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_student_import_confirmer
        FOREIGN KEY (confirmed_by_user_id)
        REFERENCES users (id)
        ON DELETE SET NULL

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;