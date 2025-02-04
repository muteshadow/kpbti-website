<!-- HEADER -->
<div class="header-short">
    <div class="container">
        <div class="logo-toggle"><a href="index.php"><img src="img/logow.png" alt=""></a></div>
        <button class="menu-toggle">☰</button>
        <div class="nav">
            <a href="news.php" class="nav_item <?php if(basename($_SERVER['PHP_SELF']) == 'news.php'){ echo 'active'; } ?>">Новини</a>
            <a href="about.php" class="nav_item <?php if(basename($_SERVER['PHP_SELF']) == 'about.php'){ echo 'active'; } ?>">Про нас</a>
            <a href="index.php" class="nav_item logo"><img src="img/logow.png" height="52" alt=""></a>
            <a href="documents.php" class="nav_item <?php if(basename($_SERVER['PHP_SELF']) == 'documents.php'){ echo 'active'; } ?>">Документи</a>
            <div class="nav_item" id="personalAccount">Особистий кабінет</div>
        </div>
    </div>
</div>

<!-- INTRO -->
<div class="intro-short">
    <div class="container">
        <div class="inner_intro-short">
            <div data-aos="fade-right" data-aos-duration="1000">
                <div class="intro_title"><?php echo $pageTitle; ?></div>
            </div>
            <div class="breadcrumbs" data-aos="fade-right" data-aos-duration="1000">
                <a href="index.php">Головна-></a> 
                <?php if (!empty($parentPageTitle)) : ?>
                    <a href="<?php echo $parentPageUrl; ?>"><?php echo $parentPageTitle; ?>-></a> 
                <?php endif; ?>
                <span><?php echo $pageTitle; ?></span>
            </div>
        </div>
    </div>
</div>