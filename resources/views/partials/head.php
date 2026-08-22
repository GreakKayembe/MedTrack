<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<title>
    <?= htmlspecialchars(
        $pageTitle ?? 'MedTrack',
        ENT_QUOTES,
        'UTF-8'
    ) ?> | MedTrack
</title>

<meta
    name="description"
    content="MedTrack - Plateforme de gestion des stages médicaux et paramédicaux"
>

<meta
    name="theme-color"
    content="#0b1f3a"
>

<meta
    name="color-scheme"
    content="light"
>


<!--
|--------------------------------------------------------------------------
| Favicons
|--------------------------------------------------------------------------
-->

<link
    rel="icon"
    type="image/png"
    href="/assets/img/logo.png"
>

<link
    rel="apple-touch-icon"
    href="/assets/img/logo.png"
>


<!--
|--------------------------------------------------------------------------
| Vendor styles
|--------------------------------------------------------------------------
-->

<link
    rel="stylesheet"
    href="/assets/vendor/bootstrap/css/bootstrap.min.css"
>

<link
    rel="stylesheet"
    href="/assets/vendor/bootstrap-icons/bootstrap-icons.css"
>



<link
    rel="stylesheet"
    href="/assets/fonts/inter/inter.css"
>


<link
    rel="stylesheet"
    href="/assets/vendor/sweetalert2/sweetalert2.min.css"
>


<!--
|--------------------------------------------------------------------------
| MedTrack Design System
|--------------------------------------------------------------------------
|
| NiceAdmin n'est plus chargé ici.
|
| L'interface principale dépend désormais de :
|
| - Bootstrap 5
| - Bootstrap Icons
| - medtrack.css
| - styles spécifiques aux pages
|--------------------------------------------------------------------------
-->

<link
    rel="stylesheet"
    href="/assets/css/medtrack.css"
>


<!--
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
-->

<link
    rel="stylesheet"
    href="/assets/css/medtrack-dashboard.css"
>


<!--
|--------------------------------------------------------------------------
| Page-specific styles
|--------------------------------------------------------------------------
|
| Permet à un contrôleur / une vue de fournir :
|
| $pageStyles = [
|     '/assets/css/academic.css',
|     ...
| ];
|--------------------------------------------------------------------------
-->

<?php if (
    !empty($pageStyles)
    && is_array($pageStyles)
): ?>

    <?php foreach (
        $pageStyles
        as $style
    ): ?>

        <?php
        $stylePath =
            trim(
                (string) $style
            );

        if ($stylePath === '') {
            continue;
        }
        ?>

        <link
            rel="stylesheet"
            href="<?= htmlspecialchars(
                $stylePath,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    <?php endforeach; ?>

<?php endif; ?>