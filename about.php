<?php
$pageTitle = "Про нас";
require "assets/header.php";
require "assets/intro-short.php";

$leaders = getAboutByCategory($conn, 'керівництво');
$employees = getAboutByCategory($conn, 'працівники');
$web_responsibles = getAboutByCategory($conn, 'відповідальні за сайт');
?>

<!-- MANAGEMENT -->
<div class="section section-short">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <div class="section_title title_center">Керівництво</div>
        </div>
        <div class="inner_section">
            <?php while ($row = $leaders->fetch_assoc()): ?>
                <div data-aos="fade-up" data-aos-duration="1000">
                    <div class="card">
                        <div class="card-circle">
                            <?php
                            // Формуємо шлях до зображення
                            $imagePath = $row['image'];

                            // Перевірка, чи існує файл за цим шляхом
                            if (!empty($row['image']) && file_exists($imagePath)): ?>
                                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                            <?php else: ?>
                                <i class="fa-solid fa-user"></i> <!-- Іконка, якщо зображення немає -->
                            <?php endif; ?>
                        </div>
                        <div class="card_title"><?= htmlspecialchars($row['name']) ?></div>
                        <div class="card_job"><?= htmlspecialchars($row['job']) ?></div>
                        <div class="card_text"><?= htmlspecialchars($row['text']) ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- WORKERS -->
<div class="section section-short">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <div class="section_title title_center title_card">Працівники</div>
        </div>
        <div class="inner_section">
            <?php while ($row = $employees->fetch_assoc()): ?>
                <div data-aos="fade-up" data-aos-duration="1000">
                    <div class="card">
                        <div class="card-circle">
                            <?php
                            // Формуємо шлях до зображення
                            $imagePath = $row['image'];

                            // Перевірка, чи існує файл за цим шляхом
                            if (!empty($row['image']) && file_exists($imagePath)): ?>
                                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                            <?php else: ?>
                                <i class="fa-solid fa-user"></i> <!-- Іконка, якщо зображення немає -->
                            <?php endif; ?>
                        </div>
                        <div class="card_title"><?= htmlspecialchars($row['name']) ?></div>
                        <div class="card_job"><?= htmlspecialchars($row['job']) ?></div>
                        <div class="card_text"><?= htmlspecialchars($row['text']) ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- ADMINISTRATION -->
<div class="section section-short">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <div class="section_title title_center title_card">Відповідальні за цей сайт</div>
        </div>
        <div class="inner_section">
            <?php while ($row = $web_responsibles->fetch_assoc()): ?>
                <div data-aos="fade-up" data-aos-duration="1000">
                    <div class="card">
                        <div class="card-circle">
                            <?php
                            // Формуємо шлях до зображення
                            $imagePath = $row['image'];

                            // Перевірка, чи існує файл за цим шляхом
                            if (!empty($row['image']) && file_exists($imagePath)): ?>
                                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                            <?php else: ?>
                                <i class="fa-solid fa-user"></i> <!-- Іконка, якщо зображення немає -->
                            <?php endif; ?>
                        </div>
                        <div class="card_title"><?= htmlspecialchars($row['name']) ?></div>
                        <div class="card_job"><?= htmlspecialchars($row['job']) ?></div>
                        <div class="card_text"><?= htmlspecialchars($row['text']) ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php
require "assets/footer.php";
?>