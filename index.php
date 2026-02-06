<?php
require "assets/header.php";
require "assets/intro.php";

if (DEMO_MODE) {
    require 'assets/demo-data/stats.php';
    require 'assets/demo-data/news.php';

    $statuses = $demoStatuses;
    
    $allNews = (array)$demoNews; 
    $news = array_slice($allNews, 0, 3);
} else {
    // Статистика
    $statuses = getOrderStatuses($conn);
    $totalUsers = getTotalUsers($conn);
    $completedOrders = getCompletedOrders($conn);
    $totalWorkers = getTotalWorkers($conn);

    $news = getNews($conn, 3);
}
?>

<!-- SECTION -->
<section class="section section_gray">
    <div class="container">
        <h3 class="section_title" data-aos="fade-up" data-aos-duration="1000">Про Бюро</h3>

        <div class="inner_section">
            <div class="section_item">
                <div data-aos="fade-up" data-aos-duration="1000">
                    <p class="section_content">Комунальне підприємство «Бюро технічної інвентаризації» Гусятинської
                        селищної ради знаходиться у смт Гусятин на Тернопільщині. Це підприємство здійснює діяльність на
                        засадах господарського розрахунку, самофінансування та чинного законодавства України. Бюро є
                        юридичною особою, має власну печатку, штамп і рахунок, займається інвентаризацією нерухомого
                        майна, а також надає послуги, що відповідають потребам місцевої громади.</p>
                </div>

                <div data-aos="fade-up" data-aos-duration="1000">
                    <a href="taxes.php" class="btn">Детальніше</a>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-duration="1000">
                <img src="img/index.jpg" width="370" height="250" alt="about image">
            </div>
        </div>

        <!-- Diagram -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="js/chart.js"></script>

        <!-- Статистика замовлень -->
        <div id="stats" class="stats_section" data-aos="fade-up" data-aos-duration="1000">
            <div class="stat" style="display: block">
                <canvas id="orderStatusChart"></canvas>
            </div>

            <div class="stat">
                <div class="number" data-target="<?= $totalUsers ?>">0</div>
                <div class="description">Зареєстрованих користувачів нашого сайту</div>
            </div>
            <div class="stat">
                <div class="number" data-target="<?= $completedOrders ?>">0</div>
                <div class="description">Клієнтів вже отримали бажані послуги</div>
            </div>
            <div class="stat">
                <div class="number" data-target="<?= $totalWorkers ?>">0</div>
                <div class="description">Працівників комунального підприємства</div>
            </div>
        </div>

        <script>
            // Дані з PHP
            var orderData = [<?= $statuses['в процесі'] ?>, <?= $statuses['скасовано'] ?>, <?= $statuses['завершено'] ?>];

            // Виклик функції
            renderOrderStatusChart('orderStatusChart', orderData);
        </script>
    </div>
</section>

<!-- NEW -->
<section class="section">
    <div class="container">
        <h3 class="section_title title_center" data-aos="fade-up" data-aos-duration="1000">Новини</h3>

        <div class="inner_section">
            <?php if (DEMO_MODE): ?>
                <?php foreach ($news as $row): ?>
                    <?php $formatted_date = date('d.m.Y', strtotime($row['date'])); ?>
                    <div class="new_item">
                        <div data-aos="fade-up" data-aos-duration="1000">
                            <img src="<?= htmlspecialchars($row['image']) ?>" class="new_image" width="370" height="250" alt="new image">
                        </div>

                        <div class="new_content" data-aos="fade-up" data-aos-duration="1000">
                            <h4 class="new_title short_new_title"><?= htmlspecialchars($row['title']) ?></h4>
                            <div class="new_data"><?= $formatted_date ?></div>
                            <p class="new_text"><?= nl2br(mb_substr($row['text'], 0, 50)) ?>...</p>
                        </div>

                        <div data-aos="fade-up" data-aos-duration="1000">
                            <a href="new.php?id=<?= $row['id'] ?? '#' ?>" class="btn">Читати повністю -></a>
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

<!-- MAP -->
<div id="map"></div>

<!-- MAP -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- JS -->
<script src="js/map.js"></script>

<?php
require "assets/footer.php";
?>