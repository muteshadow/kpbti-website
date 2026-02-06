<?php
$pageTitle = "Новини";
require "assets/header.php";
require "assets/intro-short.php";

if (DEMO_MODE) {
    require 'assets/demo-data/news.php';
    $news = $demoNews;
} else {
    $news = getNews($conn);
}
?>

<!-- NEW -->
<section class="section section-short">
    <div class="container">
        <div class="inner_section">
            <?php if (DEMO_MODE): ?>
                <?php foreach ($news as $row): ?>
                    <?php $formatted_date = date('d.m.Y', strtotime($row['date']));
                    ?>
                    <div class="new_item">
                        <div data-aos="fade-up" data-aos-duration="1000">
                            <img src="<?= htmlspecialchars($row['image']) ?>" class="new_image" width="370" height="250"
                                alt="new image">
                        </div>

                        <div class="new_content" data-aos="fade-up" data-aos-duration="1000">
                            <h4 class="new_title short_new_title"><?= htmlspecialchars($row['title']) ?></h4>
                            <div class="new_data"><?= $formatted_date ?></div>
                            <p class="new_text"><?= nl2br(mb_substr($row['text'], 0, 50)) ?>...</p>
                        </div>

                        <div data-aos="fade-up" data-aos-duration="1000">
                            <a href="new.php?id=<?= $row['id'] ?>" class="btn">Читати повністю -></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php while ($row = $news->fetch_assoc()): ?>
                    <?php
                    // Форматуємо дату в PHP
                    $formatted_date = date('d.m.Y', strtotime($row['date']));
                    ?>
                    <div class="new_item">
                        <div data-aos="fade-up" data-aos-duration="1000">
                            <img src="<?= htmlspecialchars($row['image']) ?>" class="new_image" width="370" height="250"
                                alt="new image">
                        </div>

                        <div class="new_content" data-aos="fade-up" data-aos-duration="1000">
                            <h4 class="new_title short_new_title"><?= htmlspecialchars($row['title']) ?></h4>
                            <div class="new_data"><?= $formatted_date ?></div>
                            <p class="new_text"><?= nl2br(mb_substr($row['text'], 0, 50)) ?>...</p>
                        </div>

                        <div data-aos="fade-up" data-aos-duration="1000">
                            <a href="new.php?id=<?= $row['id'] ?>" class="btn">Читати повністю -></a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
require "assets/footer.php";
?>