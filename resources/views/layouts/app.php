<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require dirname(__DIR__) . '/partials/head.php'; ?>
</head>

<body class="medtrack-app">

    <!--
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    -->

    <?php require dirname(__DIR__) . '/partials/header.php'; ?>


    <!--
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    -->

    <?php require dirname(__DIR__) . '/partials/sidebar.php'; ?>


    <!--
    |--------------------------------------------------------------------------
    | Main
    |--------------------------------------------------------------------------
    -->

    <main
        id="main"
        class="medtrack-main"
    >

        <div class="medtrack-page">

            <!-- Page header -->

            <div class="medtrack-page__header">

                <div>

                    <nav
                        aria-label="Fil d’Ariane"
                        class="medtrack-breadcrumb"
                    >
                        <ol class="breadcrumb mb-2">

                            <li class="breadcrumb-item">
                                <a href="/">
                                    MedTrack
                                </a>
                            </li>

                            <li
                                class="breadcrumb-item active"
                                aria-current="page"
                            >
                                <?= htmlspecialchars(
                                    $pageTitle
                                    ?? 'Tableau de bord',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </li>

                        </ol>
                    </nav>


                    <h1 class="medtrack-page__title">
                        <?= htmlspecialchars(
                            $pageTitle
                            ?? 'Tableau de bord',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </h1>

                </div>

            </div>


            <!-- Page content -->

            <section
                class="medtrack-page__content"
            >
                <?= $content ?>
            </section>

        </div>

    </main>


    <!--
    |--------------------------------------------------------------------------
    | Footer
    |--------------------------------------------------------------------------
    -->

    <?php require dirname(__DIR__) . '/partials/footer.php'; ?>


    <!--
    |--------------------------------------------------------------------------
    | Scripts
    |--------------------------------------------------------------------------
    -->

    <?php require dirname(__DIR__) . '/partials/scripts.php'; ?>

</body>
</html>