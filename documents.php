<?php
$pageTitle = "Документи";
require "assets/header.php";
require "assets/intro-short.php";

if (DEMO_MODE) {
    require 'assets/demo-data/documents.php';

    $normativeDocs = $demoNormativeDocs;
    $orderSamples = $demoOrderSamples;
    $servicePrices = $demoServicePrices;
} else {
    // Типы документов
    $normativeDocs = getDocumentsByType($conn, "нормативні документи"); // Нормативні документи
    $orderSamples = getDocumentsByType($conn, "зразки замовлень"); // Зразки замовлень
    $servicePrices = getDocumentsByType($conn, "вартість послуг"); // Вартість послуг
}
?>

<!-- SECTION -->
<section class="section">
    <div class="container">
        <h3 class="section_title white" data-aos="fade-up" data-aos-duration="1000">Нормативні документи</h3>

        <div class="inner_section">
            <div class="section_item">
                <div data-aos="fade-up" data-aos-duration="1000">
                    <p class="section_content white">Нормативні документи у роботі бюро технічної інвентаризації – це
                        закони, постанови, розпорядження та інші акти, що регулюють його діяльність. Вони забезпечують
                        порядок виконання послуг, визначають вимоги до технічної документації та оформлення замовлень.
                        Дотримання цих документів гарантує законність та точність усіх процедур бюро.</p>
                </div>

                <?php foreach ($normativeDocs as $doc): ?>
                    <?php
                        $link = $doc['link'];
                        $isFakeLink = DEMO_MODE && ($link === '#' || empty($link));
                    ?>

                    <div data-aos="fade-up" data-aos-duration="1000">
                        <p class="section_content documents_links">
                            <a href="<?= htmlspecialchars($isFakeLink ? $_SERVER['REQUEST_URI'] : $link) ?>" class="btn_documents"
                                <?= $isFakeLink ? '' : 'target="_blank"' ?>>-> <?= htmlspecialchars($doc['title']) ?>
                            </a>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div data-aos="fade-up" data-aos-duration="1000">
                <img src="img/documents1.jpg" width="370" height="250" alt="about">
            </div>
        </div>
    </div>
</section>

<!-- SECTION -->
<section class="section section_gray">
    <div class="container">
        <h3 class="section_title" data-aos="fade-up" data-aos-duration="1000">Зразки замовлень</h3>

        <div class="inner_section">
            <div class="section_item">
                <div data-aos="fade-up" data-aos-duration="1000">
                    <p class="section_content">Зразки замовлень допоможуть правильно оформити заявку на отримання послуг
                        бюро. Ви можете ознайомитися з прикладами документів для різних запитів, щоб уникнути помилок і
                        заощадити час. Зручні шаблони зроблять процес підготовки простішим і зрозумілішим.</p>
                </div>

                <?php foreach ($orderSamples as $doc): ?>
                    <?php
                        $link = $doc['link'];
                        $isFakeLink = DEMO_MODE && ($link === '#' || empty($link));
                    ?>

                    <div data-aos="fade-up" data-aos-duration="1000">
                        <p class="section_content documents_links">
                            <a href="<?= htmlspecialchars($isFakeLink ? $_SERVER['REQUEST_URI'] : $link) ?>" class="btn_documents"
                                <?= $isFakeLink ? '' : 'target="_blank"' ?>>-> <?= htmlspecialchars($doc['title']) ?>
                            </a>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div data-aos="fade-up" data-aos-duration="1000">
                <img src="img/documents2.jpg" width="370" height="250" alt="about">
            </div>
        </div>
    </div>
</section>

<!-- SECTION -->
<section class="section">
    <div class="container">
        <h3 class="section_title white" data-aos="fade-up" data-aos-duration="1000">Вартість послуг</h3>

        <div class="inner_section">
            <div class="section_item">
                <div data-aos="fade-up" data-aos-duration="1000">
                    <p class="section_content white">Вартість послуг бюро технічної інвентаризації визначається
                        відповідно до чинного законодавства. Ми пропонуємо прозорі розцінки для кожної послуги.
                        Ознайомтеся з детальним переліком тарифів, щоб заздалегідь спланувати витрати та отримати
                        необхідну інформацію.</p>
                </div>

                <?php foreach ($servicePrices as $doc): ?>
                    <?php
                        $link = $doc['link'];
                        $isFakeLink = DEMO_MODE && ($link === '#' || empty($link));
                    ?>
                    
                    <div data-aos="fade-up" data-aos-duration="1000">
                        <p class="section_content documents_links">
                            <a href="<?= htmlspecialchars($isFakeLink ? $_SERVER['REQUEST_URI'] : $link) ?>" class="btn_documents"
                                <?= $isFakeLink ? '' : 'target="_blank"' ?>>-> <?= htmlspecialchars($doc['title']) ?>
                            </a>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div data-aos="fade-up" data-aos-duration="1000">
                <img src="img/documents3.jpg" width="370" height="250" alt="about">
            </div>
        </div>
    </div>
</section>

<?php
require "assets/footer.php";
?>