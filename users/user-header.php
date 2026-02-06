<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "../../assets/config.php";
include "../../assets/function.php";
include "../../assets/users_function.php";
?>

<!DOCTYPE html>
<html lang="ua">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/combined.css.php">

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&family=Zilla+Slab:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">

    <!-- FONTAWESOME -->
    <link href="https://use.fontawesome.com/releases/v6.7.0/css/all.css" rel="stylesheet">

    <!-- JS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <script>
        window.DEMO_MODE = <?= DEMO_MODE ? 'true' : 'false' ?>;
    </script>

    <title>КП БТІ</title>
</head>

<body>

    <!-- HEADER -->
    <header class="header-short">
        <div class="container">
            <div class="logout_container">
                <a href="../logout.php" class="logout">Вихід</a>
            </div>
        </div>
    </header>

    <!-- INTRO -->
    <section class="intro-short">
        <div class="container">
            <div class="inner_intro-short">
                <h3 class="intro_title" data-aos="fade-right" data-aos-duration="1000"><?php echo $pageTitle; ?></h3>

                <div class="breadcrumbs" data-aos="fade-right" data-aos-duration="1000">
                    <?php if (!empty($parentPageTitle)): ?>
                        <a href="<?php echo $parentPageUrl; ?>"><?php echo $parentPageTitle; ?>-></a>
                    <?php endif; ?>

                    <span><?php echo $pageTitle; ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Вкладки admin -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <div class="admin-tabs" data-aos="fade-up" data-aos-duration="1000">
            <div class="container">
                <nav class="admin-nav">
                    <a href="admin.php" 
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : '' ?>">Користувачі</a>

                    <a href="<?= DEMO_MODE ? '#' : 'admin_appointments.php' ?>" 
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin_appointments.php' ? 'active' : '' ?> <?= DEMO_MODE ? 'disabled-link' : '' ?>">Замовлення</a>
                    
                    <a href="<?= DEMO_MODE ? '#' : 'admin_services.php' ?>" 
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin_services.php' ? 'active' : '' ?> <?= DEMO_MODE ? 'disabled-link' : '' ?>">Послуги</a>
                    
                    <a href="<?= DEMO_MODE ? '#' : 'admin_categories.php' ?>" 
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin_categories.php' ? 'active' : '' ?> <?= DEMO_MODE ? 'disabled-link' : '' ?>">Категорії</a>
                    
                    <a href="<?= DEMO_MODE ? '#' : 'admin_documents.php' ?>" 
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin_documents.php' ? 'active' : '' ?> <?= DEMO_MODE ? 'disabled-link' : '' ?>">Документи</a>
                    
                    <a href="<?= DEMO_MODE ? '#' : 'admin_news.php' ?>" 
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin_news.php' ? 'active' : '' ?> <?= DEMO_MODE ? 'disabled-link' : '' ?>">Новини</a>
                    
                    <a href="<?= DEMO_MODE ? '#' : 'admin_stats.php' ?>" 
                    class="<?= basename($_SERVER['PHP_SELF']) == 'admin_stats.php' ? 'active' : '' ?> <?= DEMO_MODE ? 'disabled-link' : '' ?>">Статистика</a>
                </nav>
            </div>
        </div>
    <?php endif; ?>