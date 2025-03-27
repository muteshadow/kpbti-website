<?php
$pageTitle = "Новини";
require "assets/header.php";
require "assets/intro-short.php";

$news = getNews($conn);
?>

<!-- NEW -->
<div class="section section-short">
    <div class="container">
        <div class="inner_section">
            <?php while ($row = $news->fetch_assoc()): ?>
                <?php
                // Форматуємо дату в PHP
                $formatted_date = date('d.m.Y', strtotime($row['date']));
                ?>
                <div class="new_item">
                    <div data-aos="fade-up" data-aos-duration="1000">
                        <img src="<?= htmlspecialchars($row['image']) ?>" class="new_image" alt="">
                    </div>
                    <div data-aos="fade-up" data-aos-duration="1000">
                        <div class="new_content">
                            <div class="new_title"><?= htmlspecialchars($row['title']) ?></div>
                            <div class="new_data"><?= $formatted_date ?></div>
                            <div class="new_text"><?= nl2br(mb_substr($row['text'], 0, 50)) ?>...</div>
                        </div>
                    </div>
                    <div data-aos="fade-up" data-aos-duration="1000">
                        <a href="new.php?id=<?= $row['id'] ?>" class="btn">Читати повністю -></a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php
require "assets/footer.php";
?>