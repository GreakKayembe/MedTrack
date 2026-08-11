<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require dirname(__DIR__) . '/partials/head.php'; ?>
</head>

<body>

    <?php require dirname(__DIR__) . '/partials/header.php'; ?>

    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1><?= htmlspecialchars($pageTitle ?? 'Tableau de bord') ?></h1>

            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/">MedTrack</a>
                    </li>

                    <li class="breadcrumb-item active">
                        <?= htmlspecialchars($pageTitle ?? 'Tableau de bord') ?>
                    </li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <?= $content ?>
        </section>

    </main>

    <?php require dirname(__DIR__) . '/partials/footer.php'; ?>

    <?php require dirname(__DIR__) . '/partials/scripts.php'; ?>

</body>
</html>
