<?php
require "assets/header.php";
require "assets/intro.php";

$news = getNews($conn, 3);
?>

<!-- SECTION -->
<div class="section gray">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <div class="section_title">Про Бюро</div>
        </div>
        <div class="inner_section gray">
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
            <div data-aos="fade-up" data-aos-duration="1000"><img src="img/index.jpg" width="370" height="250" alt="">
            </div>
        </div>
    </div>
</div>

<!-- NEW -->
<div class="section">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <div class="section_title title_center">Новини</div>
        </div>
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

<!-- MAP -->
<div id="map"></div>

<!-- MAP -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<!-- JS -->
<script src="js/map.js"></script>

<?php
require "assets/footer.php";
?>