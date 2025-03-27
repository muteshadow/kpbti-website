<?php
$parentPageTitle = 'Наші послуги';
$parentPageUrl = 'services.php';
$pageTitle = "Для юридичних осіб";

require "assets/header.php";
require "assets/intro-short.php";

$services = getServices($conn);
?>

<!-- SERVICES -->
<div class="section section-short">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <div class="section_title title_center">Можливі замовлення</div>
        </div>
        <div class="inner_section">
            <?php foreach ($services as $service): ?>
                <div data-aos="fade-up" data-aos-duration="1000">
                    <div class="card service">
                        <div class="card-circle">
                            <img src="<?= htmlspecialchars($service['image']) ?>" alt="">
                        </div>
                        <div class="card_title"><?= htmlspecialchars($service['category_name']) ?></div>
                        <?php $serviceList = explode(", ", $service['services']);
                        foreach ($serviceList as $singleService): ?>
                            <a href="service.php?property=<?= urlencode($service['category_name']) ?>&service=<?= urlencode($singleService) ?>&personType=legal"
                                class="card_service">
                                -> <?= htmlspecialchars($singleService) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
require "assets/footer.php";
?>