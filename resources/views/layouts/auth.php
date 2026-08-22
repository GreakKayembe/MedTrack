<?php

declare(strict_types=1);

/**
 * @var string $content
 * @var string|null $pageTitle
 */

$content = $content ?? '';
$pageTitle = $pageTitle ?? 'MedTrack';

?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <?php require dirname(__DIR__) . '/partials/head.php'; ?>

    <link
        rel="stylesheet"
        href="/assets/css/medtrack-auth.css"
    >

</head>

<body class="mt-auth-body">

    <main>
        <?= $content ?>
    </main>

    <?php require dirname(__DIR__) . '/partials/scripts.php'; ?>

    <script src="/assets/js/medtrack-auth.js"></script>
    <script src="/assets/js/medtrack-login.js"></script>
    <script src="/assets/js/medtrack-forgot-password.js"></script>


</body>

</html>