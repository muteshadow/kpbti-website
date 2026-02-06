<?php
$parentPageTitle = 'Вихід';
$parentPageUrl = '../logout.php';
$pageTitle = "Управління новинами";

require "../user-header.php";
preventCaching();

if (DEMO_MODE) {
    $_SESSION['role'] = 'admin';
    
    require '../../assets/demo-data/news.php';

    $newsList = iterator_to_array($demoNews);
} else {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../../index.php");
        exit();
    }

    global $conn;

    // Додавання новини
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_news'])) {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $text = mysqli_real_escape_string($conn, $_POST['text']);
        $date = date('Y-m-d');

        // Обробка зображення
        $imageName = '';
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = '../../img';
            if (!is_dir($uploadDir))
                mkdir($uploadDir, 0777, true);
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . '/' . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        }

        mysqli_query($conn, "INSERT INTO news (title, date, text, image) VALUES ('$title', '$date', '$text', '$imageName')");
        header("Location: admin_news.php");
        exit();
    }
    // Видалення новини
    if (isset($_GET['delete_id'])) {
        $id = (int) $_GET['delete_id'];
        mysqli_query($conn, "DELETE FROM news WHERE id = $id");
        header("Location: admin_news.php");
        exit();
    }

    // Вибір кількості новин на головній
    if (isset($_POST['main_news_limit'])) {
        $limit = (int) $_POST['main_news_limit'];
        file_put_contents('../js/news_limit.txt', $limit);
        header("Location: admin_news.php");
        exit();
    }

    // Отримання всіх новин
    $res = mysqli_query($conn, "SELECT * FROM news ORDER BY date DESC");
    $newsList = mysqli_fetch_all($res, MYSQLI_ASSOC);
}
?>

<section class="section section-short">
    <div class="container">
        <h3 class="section_title title_center" data-aos="fade-up" data-aos-duration="1000">Управління новинами</h3>

        <!-- Форма додавання новини -->
        <form method="POST" enctype="multipart/form-data" style="margin-bottom: 30px;" data-aos="fade-up"
            data-aos-duration="1000">
            <input type="hidden" name="add_news" value="1">
            <label>Заголовок новини:</label><br>
            <input type="text" name="title" required style="width: 100%;"><br><br>

            <label>Текст новини:</label><br>
            <textarea name="text" required rows="5" style="width: 100%;"></textarea><br><br>

            <label>Зображення (опціонально):</label><br>
            <input type="file" name="image" accept="image/*"><br><br>

            <button type="submit" class="btn_documents">Додати новину</button>
        </form>

        <!-- Відображення новин -->
        <div class="inner_section card_container">
            <?php foreach ($newsList as $news): ?>
                <div class="appointment-card" data-aos="fade-up" data-aos-duration="1000">
                    <h4 class="card_title"><?= htmlspecialchars($news['title']) ?></h4>
                    <div class="card_job">Опубліковано: <?= date('d.m.Y', strtotime($news['date'])) ?></div>
                    <?php if (!empty($news['image'])): ?>
                        <div><img src="../../<?= htmlspecialchars($news['image']) ?>" alt="img"
                                style="width: 100%; max-width: 300px; margin-top: 10px;"></div>
                    <?php endif; ?>
                    <div class="card_job" style="margin-top: 10px; text-align: justify;">
                        <?= nl2br(htmlspecialchars($news['text'])) ?>
                    </div>
                    <div class="modal_btn_container">
                        <button class="btn_documents" disabled>Редагувати</button>
                        <a class="btn_documents" href="?delete_id=<?= $news['id'] ?>"
                            onclick="return confirm('Видалити новину?')">Видалити</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script src="../js/admin.js"></script>
<?php require "../user-footer.php"; ?>