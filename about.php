<?php
$pageTitle = "Про нас";
require "assets/header.php";
require "assets/intro-short.php";

if (DEMO_MODE) {
    require 'assets/demo-data/about.php';

    $leaders = $demoLeaders;
    $employees = $demoEmployees;
    $web_responsibles = $demoWebResponsibles;
} else {
    $leaders = getAboutByCategory($conn, 'керівництво');
    $employees = getAboutByCategory($conn, 'працівники');
    $web_responsibles = getAboutByCategory($conn, 'відповідальні за сайт');
}
?>

<!-- MANAGEMENT -->
<section class="section section-short">
    <div class="container">
        <h3 class="section_title title_center" data-aos="fade-up" data-aos-duration="1000">Керівництво</h3>

        <div class="inner_section card_container">
            <?php if (DEMO_MODE): ?>
                <?php foreach ($leaders as $row): ?>
                    <div class="card" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-circle">
                            <img
                                src="<?= htmlspecialchars($row['image']) ?>"
                                alt="<?= htmlspecialchars($row['name']) ?>"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                            >
                            <i class="fa-solid fa-user" style="display:none;"></i>
                        </div>

                        <h4 class="card_title"><?= htmlspecialchars($row['name']) ?></h4>
                        <div class="card_job"><?= htmlspecialchars($row['job']) ?></div>
                        <p class="card_text"><?= htmlspecialchars($row['text']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php while ($row = $leaders->fetch_assoc()): ?>
                    <div class="card" data-aos="fade-up" data-aos-duration="1000">
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
                        <h4 class="card_title"><?= htmlspecialchars($row['name']) ?></h4>
                        <div class="card_job"><?= htmlspecialchars($row['job']) ?></div>
                        <p class="card_text"><?= htmlspecialchars($row['text']) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- WORKERS -->
<section class="section section-short">
    <div class="container">
        <h3 class="section_title title_center" data-aos="fade-up" data-aos-duration="1000">Працівники</h3>

        <div class="inner_section card_container">
            <?php if (DEMO_MODE): ?>
                <?php foreach ($employees as $row): ?>
                    <div class="card" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-circle">
                            <?php
                            // Формуємо шлях до зображення
                            $imagePath = $row['image'];

                            // Перевірка, чи існує файл за цим шляхом
                            if (!empty($row['image'])): ?>
                                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                            <?php else: ?>
                                <i class="fa-solid fa-user"></i> <!-- Іконка, якщо зображення немає -->
                            <?php endif; ?>
                        </div>
                        <h4 class="card_title"><?= htmlspecialchars($row['name']) ?></h4>
                        <div class="card_job"><?= htmlspecialchars($row['job']) ?></div>
                        <p class="card_text"><?= htmlspecialchars($row['text']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php while ($row = $employees->fetch_assoc()): ?>
                    <div class="card" data-aos="fade-up" data-aos-duration="1000">
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
                        <h4 class="card_title"><?= htmlspecialchars($row['name']) ?></h4>
                        <div class="card_job"><?= htmlspecialchars($row['job']) ?></div>
                        <p class="card_text"><?= htmlspecialchars($row['text']) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ADMINISTRATION -->
<section class="section section-short">
    <div class="container">
        <h3 class="section_title title_center" data-aos="fade-up" data-aos-duration="1000">Відповідальні за цей сайт
        </h3>

        <div class="inner_section card_container">
            <?php if (DEMO_MODE): ?>
                <?php foreach ($web_responsibles as $row): ?>
                    <div class="card" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-circle">
                            <?php
                            // Формуємо шлях до зображення
                            $imagePath = $row['image'];

                            // Перевірка, чи існує файл за цим шляхом
                            if (!empty($row['image'])): ?>
                                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                            <?php else: ?>
                                <i class="fa-solid fa-user"></i> <!-- Іконка, якщо зображення немає -->
                            <?php endif; ?>
                        </div>
                        <h4 class="card_title"><?= htmlspecialchars($row['name']) ?></h4>
                        <div class="card_job"><?= htmlspecialchars($row['job']) ?></div>
                        <p class="card_text"><?= htmlspecialchars($row['text']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php while ($row = $web_responsibles->fetch_assoc()): ?>
                    <div class="card" data-aos="fade-up" data-aos-duration="1000">
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
                        <h4 class="card_title"><?= htmlspecialchars($row['name']) ?></h4>
                        <div class="card_job"><?= htmlspecialchars($row['job']) ?></div>
                        <p class="card_text"><?= htmlspecialchars($row['text']) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
require "assets/footer.php";
?>