<?php
$parentPageTitle = 'Наші послуги';
$parentPageUrl = 'services.php';
$pageTitle = "Для юридичних осіб";

require "assets/header.php";
require "assets/intro-short.php";

if (DEMO_MODE) {
    require 'assets/demo-data/services.php';
    $services = $demoServices['legal'];
} else {
    $services = getLegalServices($conn);
}
?>

<!-- SERVICES -->
<section class="section section-short">
    <div class="container">
        <div class="section_title title_center" data-aos="fade-up" data-aos-duration="1000">Можливі замовлення</div>

        <div class="inner_section card_container">
            <?php if (DEMO_MODE): ?>
                <?php
                // Групуємо послуги за категоріями тільки для DEMO
                $groupedServices = [];
                foreach ($services as $service) {
                    $category = $service['category_name'];
                    if (!isset($groupedServices[$category])) {
                        $groupedServices[$category] = [
                            'image' => $service['image'],
                            'services' => []
                        ];
                    }
                    $groupedServices[$category]['services'][] = $service['service_name'];
                }

                foreach ($groupedServices as $categoryName => $data): ?>
                    <div class="card" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-circle">
                            <img src="<?= htmlspecialchars($data['image']) ?>"
                                 alt="<?= htmlspecialchars($categoryName) ?>">
                        </div>

                        <h4 class="card_title"><?= htmlspecialchars($categoryName) ?></h4>

                        <?php foreach ($data['services'] as $singleService): ?>
                            <a href="service.php?property=<?= urlencode($categoryName) ?>&service=<?= urlencode($singleService) ?>&personType=legal"
                               class="card_service">
                                -> <?= htmlspecialchars($singleService) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($services as $service): ?>
                    <div class="card" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-circle">
                            <img src="<?= htmlspecialchars($service['image']) ?>"
                                alt="<?= htmlspecialchars($service['image']) ?>">
                        </div>

                        <h4 class="card_title"><?= htmlspecialchars($service['category_name']) ?></h4>
                        <?php $serviceList = explode(", ", $service['services']);
                        foreach ($serviceList as $singleService): ?>
                            <a href="service.php?property=<?= urlencode($service['category_name']) ?>&service=<?= urlencode($singleService) ?>&personType=legal"
                                class="card_service">
                                -> <?= htmlspecialchars($singleService) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
require "assets/footer.php";
?>