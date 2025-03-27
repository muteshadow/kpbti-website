<?php
$pageTitle = "Документи";
require "assets/header.php";
require "assets/intro-short.php";

// Типы документов
$normativeDocs = getDocumentsByType($conn, "нормативні документи"); // Нормативні документи
$orderSamples = getDocumentsByType($conn, "зразки замовлень"); // Зразки замовлень
$servicePrices = getDocumentsByType($conn, "вартість послуг"); // Вартість послуг
?>

<!-- SECTION -->
<div class="section white">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <div class="section_title white">Нормативні документи</div>
        </div>
        <div class="inner_section">
            <div class="section_item">
                <div data-aos="fade-up" data-aos-duration="1000">
                    <p class="section_content white">Нормативні документи у роботі бюро технічної інвентаризації – це
                        закони, постанови, розпорядження та інші акти, що регулюють його діяльність. Вони забезпечують
                        порядок виконання послуг, визначають вимоги до технічної документації та оформлення замовлень.
                        Дотримання цих документів гарантує законність та точність усіх процедур бюро.</p>
                </div>
                <?php foreach ($normativeDocs as $doc): ?>
                    <div data-aos="fade-up" data-aos-duration="1000">
                        <p class="section_content"><a href="<?= htmlspecialchars($doc['link']) ?>" class="btn_documents"
                                target="_blank">-> <?= htmlspecialchars($doc['title']) ?></a></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div data-aos="fade-up" data-aos-duration="1000"><img src="img/documents1.jpg" width="370" height="250"
                    alt=""></div>
        </div>
    </div>
</div>

<!-- SECTION -->
<div class="section gray">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <div class="section_title">Зразки замовлень</div>
        </div>
        <div class="inner_section gray">
            <div class="section_item">
                <div data-aos="fade-up" data-aos-duration="1000">
                    <p class="section_content">Зразки замовлень допоможуть правильно оформити заявку на отримання послуг
                        бюро. Ви можете ознайомитися з прикладами документів для різних запитів, щоб уникнути помилок і
                        заощадити час. Зручні шаблони зроблять процес підготовки простішим і зрозумілішим.</p>
                </div>
                <?php foreach ($orderSamples as $doc): ?>
                    <div data-aos="fade-up" data-aos-duration="1000">
                        <p class="section_content"><a href="<?= htmlspecialchars($doc['link']) ?>"
                                class="btn_documents btn_gray" target="_blank">-> <?= htmlspecialchars($doc['title']) ?></a>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div data-aos="fade-up" data-aos-duration="1000"><img src="img/documents2.jpg" width="370" height="250"
                    alt=""></div>
        </div>
    </div>
</div>

<!-- SECTION -->
<div class="section">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <div class="section_title white">Вартість послуг</div>
        </div>
        <div class="inner_section">
            <div class="section_item">
                <div data-aos="fade-up" data-aos-duration="1000">
                    <p class="section_content white">Вартість послуг бюро технічної інвентаризації визначається
                        відповідно до чинного законодавства. Ми пропонуємо прозорі розцінки для кожної послуги.
                        Ознайомтеся з детальним переліком тарифів, щоб заздалегідь спланувати витрати та отримати
                        необхідну інформацію.</p>
                </div>
                <?php foreach ($servicePrices as $doc): ?>
                    <div data-aos="fade-up" data-aos-duration="1000">
                        <p class="section_content"><a href="<?= htmlspecialchars($doc['link']) ?>" class="btn_documents"
                                target="_blank">-> <?= htmlspecialchars($doc['title']) ?></a></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div data-aos="fade-up" data-aos-duration="1000"><img src="img/documents3.jpg" width="370" height="250"
                    alt=""></div>
        </div>
    </div>
</div>

<?php
require "assets/footer.php";
?>