<?php

declare(strict_types=1);

/** @var array<string, mixed>|null $hospital */

$hospital =
    is_array(
        $hospital
        ?? null
    )
        ? $hospital
        : [];

$isEdit =
    isset(
        $hospital['id']
    );
?>

<div class="row g-3">

    <div class="col-md-4">

        <label class="form-label">
            Code institutionnel
        </label>

        <input
            type="text"
            name="code"
            class="form-control"
            required
            maxlength="50"
            value="<?= htmlspecialchars(
                (string) (
                    $hospital['code']
                    ?? ''
                ),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    </div>


    <div class="col-md-8">

        <label class="form-label">
            Nom de l’hôpital
        </label>

        <input
            type="text"
            name="name"
            class="form-control"
            required
            value="<?= htmlspecialchars(
                (string) (
                    $hospital['name']
                    ?? ''
                ),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Province
        </label>

        <input
            type="text"
            name="province"
            class="form-control"
            value="<?= htmlspecialchars(
                (string) (
                    $hospital['province']
                    ?? ''
                ),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Ville
        </label>

        <input
            type="text"
            name="city"
            class="form-control"
            value="<?= htmlspecialchars(
                (string) (
                    $hospital['city']
                    ?? ''
                ),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Téléphone
        </label>

        <input
            type="text"
            name="phone"
            class="form-control"
            value="<?= htmlspecialchars(
                (string) (
                    $hospital['phone']
                    ?? ''
                ),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Email
        </label>

        <input
            type="email"
            name="email"
            class="form-control"
            value="<?= htmlspecialchars(
                (string) (
                    $hospital['email']
                    ?? ''
                ),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    </div>


    <div class="col-md-6">

        <label class="form-label">
            Adresse
        </label>

        <input
            type="text"
            name="address"
            class="form-control"
            value="<?= htmlspecialchars(
                (string) (
                    $hospital['address']
                    ?? ''
                ),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Niveau de l’établissement
        </label>

        <input
            type="number"
            name="facility_level"
            min="0"
            class="form-control"
            value="<?= htmlspecialchars(
                (string) (
                    $hospital['facility_level']
                    ?? ''
                ),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Spécialité
        </label>

        <input
            type="text"
            name="specialty"
            maxlength="150"
            class="form-control"
            value="<?= htmlspecialchars(
                (string) (
                    $hospital['specialty']
                    ?? ''
                ),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Capacité de stage
        </label>

        <input
            type="number"
            name="internship_capacity"
            min="0"
            class="form-control"
            value="<?= (int) (
                $hospital['internship_capacity']
                ?? 0
            ) ?>"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Statut d’accréditation
        </label>

        <?php
        $selectedAccreditation =
            (string) (
                $hospital['accreditation_status']
                ?? 'PENDING'
            );
        ?>

        <select
            name="accreditation_status"
            class="form-select"
        >

            <?php foreach (
                [
                    'PENDING' => 'En attente',
                    'ACCREDITED' => 'Accrédité',
                    'SUSPENDED' => 'Suspendu',
                    'REVOKED' => 'Révoqué',
                ]
                as $value => $label
            ): ?>

                <option
                    value="<?= $value ?>"
                    <?= $selectedAccreditation === $value
                        ? 'selected'
                        : '' ?>
                >
                    <?= $label ?>
                </option>

            <?php endforeach; ?>

        </select>

    </div>


    <?php if ($isEdit): ?>

        <div class="col-md-4">

            <label class="form-label">
                Statut MedTrack
            </label>

            <?php
            $selectedStatus =
                (string) (
                    $hospital['status']
                    ?? 'ACTIVE'
                );
            ?>

            <select
                name="status"
                class="form-select"
            >

                <?php foreach (
                    [
                        'ACTIVE' => 'Actif',
                        'INACTIVE' => 'Inactif',
                        'SUSPENDED' => 'Suspendu',
                    ]
                    as $value => $label
                ): ?>

                    <option
                        value="<?= $value ?>"
                        <?= $selectedStatus === $value
                            ? 'selected'
                            : '' ?>
                    >
                        <?= $label ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

    <?php endif; ?>


    <div class="col-md-4">

        <label class="form-label">
            Latitude
        </label>

        <input
            type="number"
            step="0.00000001"
            min="-90"
            max="90"
            name="latitude"
            class="form-control"
            value="<?= htmlspecialchars(
                (string) (
                    $hospital['latitude']
                    ?? ''
                ),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    </div>


    <div class="col-md-4">

        <label class="form-label">
            Longitude
        </label>

        <input
            type="number"
            step="0.00000001"
            min="-180"
            max="180"
            name="longitude"
            class="form-control"
            value="<?= htmlspecialchars(
                (string) (
                    $hospital['longitude']
                    ?? ''
                ),
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    </div>

</div>