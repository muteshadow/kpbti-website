<?php
require "assets/header.php";

// Отримуємо параметри з URL
$property = isset($_GET['property']) ? $_GET['property'] : '';
$serviceName = isset($_GET['service']) ? $_GET['service'] : '';
$personType = isset($_GET['personType']) ? $_GET['personType'] : '';

// Отримуємо URL та заголовок для різних типів осіб
$pageInfo = getParentPageInfo($personType);
$parentPageUrl = $pageInfo['url'];
$parentPageTitle = $pageInfo['title'];

// Заголовок сторінки
$pageTitle = htmlspecialchars($property) . ": " . htmlspecialchars(truncateWords($serviceName));

// Виводимо короткий інтро (якщо є)
require "assets/intro-short.php";

if (defined('DEMO_MODE') && DEMO_MODE) {
    require 'assets/demo-data/services.php';
    $servicesArray = $personType === 'legal' ? $demoServices['legal'] : $demoServices['individual'];

    $service = null;
    $documents = [];
    $categoryImage = 'img/no-image.jpg';

    foreach ($servicesArray as $s) {
        if ($s['category_name'] === $property && $s['service_name'] === $serviceName) {
            $service = $s;
            $categoryImage = $s['image'] ?? 'img/no-image.jpg';

            // Вивід документів конкретної послуги
            if (!empty($s['document_ids'])) {
                foreach ($s['document_ids'] as $docId) {
                    if (isset($demoServices['documents'][$docId])) {
                        $documents[] = $demoServices['documents'][$docId];
                    }
                }
            }
            break;
        }
    }
} else {
    // Отримання послуги залежно від типу особи
    $service = null;
    if ($personType == 'individual') {
        $service = getServiceIdByNameIndividual($conn, $serviceName, $property);
    } elseif ($personType == 'legal') {
        $service = getServiceIdByNameLegal($conn, $serviceName, $property);
    }

    // Отримання документів для цієї послуги
    $documents = $service ? getDocumentsByIds($conn, $service['document_id']) : [];

    // Отримання зображення категорії
    $categoryImage = getCategoryImage($conn, $property);
}
?>

<section class="section section-short">
    <div class="container">
        <h3 class="section_title" data-aos="fade-up" data-aos-duration="1000">Необхідні документи</h3>

        <div class="inner_section">
            <div class="section_item">
                <?php if (count($documents) > 0): ?>
                    <div data-aos="fade-up" data-aos-duration="1000">
                        <p class="section_content white">
                            <?php foreach ($documents as $document): ?>
                                -> <?= htmlspecialchars($document) ?><br><br>
                            <?php endforeach; ?>
                        </p>
                    </div>
                <?php else: ?>
                    <p>Документи для цієї послуги не знайдено.</p>
                <?php endif; ?>
            </div>

            <div data-aos="fade-up" data-aos-duration="1000">
                <?php if ($categoryImage): ?>
                    <img src="<?= htmlspecialchars($categoryImage) ?>" width="370" height="250"
                        alt="<?= htmlspecialchars($categoryImage) ?>">
                <?php else: ?>
                    <img src="img/no-image.jpg" width="370" height="250" alt="no image">
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
require "assets/footer.php";
?>