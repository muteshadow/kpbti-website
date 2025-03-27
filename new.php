<?php
require "assets/header.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Новину не знайдено.";
    exit;
}

$news_id = (int) $_GET['id'];
$news_item = getNewsById($conn, $news_id);

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

<div class="section white">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <div class="new_title new_title_section"><?= htmlspecialchars($news_item['title']) ?></div>
            <div class="new_data new_data_section"><?= date("d.m.Y", strtotime($news_item['date'])) ?></div>
        </div>
        <div class="inner_section new_section">
            <div class="section_item">
                <div data-aos="fade-up" data-aos-duration="1000">
                    <p class="section_content white">
                        <?= nl2br(htmlspecialchars($news_item['text'])) ?>
                    </p>
                </div>
                <div data-aos="fade-up" data-aos-duration="1000">
                    <p class="section_content"><a href="news.php" class="btn"><- Назад до новин</a></p>
                </div>
            </div>
            <div data-aos="fade-up" data-aos-duration="1000">
                <img src="<?= htmlspecialchars($news_item['image'], ENT_QUOTES) ?>" width="370" height="250" ?>
            </div>
        </div>
    </div>
</div>

<?php
require "assets/footer.php";
?>