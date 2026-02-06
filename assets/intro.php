<!-- HEADER -->
<header class="header">
    <div class="container">
        <div class="logo-toggle">
            <a href="index.php"><img src="img/logow.png" height="52" alt="logo"></a>
            <button class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
        <nav class="nav">
            <a href="news.php" class="nav_item <?php if (basename($_SERVER['PHP_SELF']) == 'news.php') {
                echo 'active';
            } ?>">Новини</a>
            <a href="about.php" class="nav_item <?php if (basename($_SERVER['PHP_SELF']) == 'about.php') {
                echo 'active';
            } ?>">Про
                нас</a>
            <a href="index.php" class="nav_item logo"><img src="img/logow.png" alt="logo"></a>
            <a href="documents.php" class="nav_item <?php if (basename($_SERVER['PHP_SELF']) == 'documents.php') {
                echo 'active';
            } ?>">Документи</a>
            <div class="nav_item" id="personalAccount">Особистий кабінет</div>
        </nav>
    </div>
</header>

<!-- INTRO -->
<section class="intro">
    <div class="container">
        <div class="inner_intro">
            <div data-aos="fade-right" data-aos-duration="1000">
                <h2 class="intro_suptitle">Комунальне підприємство Гусятинської селищної ради</h2>
                <h1 class="intro_title">"Бюро технічної інвентаризації" Гусятинської селищної ради</h1>
            </div>
            <div data-aos="fade-up" data-aos-duration="1000">
                <a href="services.php" class="btn">Наші послуги</a>
            </div>
        </div>
    </div>
</section>