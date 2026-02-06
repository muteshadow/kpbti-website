<?php
require "assets/header.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Новину не знайдено.";
    exit;
}

$news_id = (int) $_GET['id'];

if (DEMO_MODE) {
    require 'assets/demo-data/news.php';
    $news_item = getDemoNewsById($news_id);
} else {
    $news_item = getNewsById($conn, $news_id);
}

if (!$news_item) {
    echo "Новину не знайдено.";
    exit;
}

$isNewsPage = true;
$parentPageTitle = 'Новини';
$parentPageUrl = 'news.php';
$pageTitle = "Новини: " . htmlspecialchars($news_item['title']);

require "assets/intro-short.php";
?>

<section class="section section-short">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <h3 class="section_title new_title"><?= htmlspecialchars($news_item['title']) ?></h3>
            <div class="new_data new_data_section"><?= date("d.m.Y", strtotime($news_item['date'])) ?></div>
        </div>

        <div class="inner_section new_section">
            <div class="section_item">
                <div data-aos="fade-up" data-aos-duration="1000">
                    <p class="section_content">
                        <?= nl2br(htmlspecialchars($news_item['text'])) ?>
                    </p>
                </div>

                <div data-aos="fade-up" data-aos-duration="1000">
                    <a href="news.php" class="btn"><- Назад до новин</a>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-duration="1000">
                <img src="<?= htmlspecialchars($news_item['image'], ENT_QUOTES) ?>" class="new_image" width="370"
                    height="250" alt="<?= htmlspecialchars($news_item['image'], ENT_QUOTES) ?>" ?>
            </div>
        </div>
    </div>
</section>

<?php
require "assets/footer.php";
?>