<?php
$parentPageTitle = 'Вихід';
$parentPageUrl = '../logout.php';
$pageTitle = "Управління категоріями";

require "../user-header.php";
preventCaching();

if (DEMO_MODE) {
    $_SESSION['role'] = 'admin';
    
    require '../../assets/demo-data/services.php';

    $uniqueCategories = [];
    foreach ($demoServices as $personType => $services) {
        if ($personType === 'documents') continue; 
        foreach ($services as $s) {
            $catName = $s['category_name'];
            // якщо ще не додали цю категорію
            if (!isset($uniqueCategories[$catName])) {
                $uniqueCategories[$catName] = [
                    'category_name' => $catName,
                    'image' => $s['image'] // просто беремо першу картинку
                ];
            }
        }
    }
} else {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../../index.php");
        exit();
    }

    // --- Підключення до БД ---
    global $conn;

    // --- Додавання нової категорії ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $imagePath = '';

        if (!empty($_FILES['image']['name'])) {
            $targetDir = "../../img/";
            $imageName = basename($_FILES['image']['name']);
            $targetFile = $targetDir . $imageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = "img/" . $imageName;
            }
        }

        if ($title && $imagePath) {
            mysqli_query($conn, "INSERT INTO categories (title, image) VALUES ('$title', '$imagePath')");
        }
        header("Location: admin_categories.php");
        exit();
    }

    // --- Видалення категорії ---
    if (isset($_GET['delete_id'])) {
        $id = (int) $_GET['delete_id'];
        mysqli_query($conn, "DELETE FROM categories WHERE id = $id");
        header("Location: admin_categories.php");
        exit();
}

// --- Всі категорії ---
$result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<section class="section section-short">
    <div class="container">
        <h3 class="section_title title_center" data-aos="fade-up" data-aos-duration="1000">Управління категоріями</h3>

        <!-- Форма додавання -->
        <form method="POST" enctype="multipart/form-data" style="margin-bottom: 30px;" data-aos="fade-up"
            data-aos-duration="1000">
            <input type="hidden" name="add_category" value="1">
            <label>Назва категорії:</label><br>
            <input type="text" name="title" required><br><br>

            <label>Зображення:</label><br>
            <input type="file" name="image" accept="image/*" required><br><br>

            <button type="submit" class="btn_documents">Додати категорію</button>
        </form>

        <!-- Список категорій -->
        <div class="inner_section card_container">
            <?php if (DEMO_MODE): ?>
                <?php foreach ($uniqueCategories as $cat): ?>
                    <div class="appointment-card" data-aos="fade-up" data-aos-duration="1000">
                        <img src="../../<?= htmlspecialchars($cat['image']) ?>" alt="Category Image"
                            style="width: 100%; height: auto; border-radius: 10px;">
                        <h4 class="card_title"><?= htmlspecialchars($cat['category_name']) ?></h4>

                        <div class="modal_btn_container">
                            <button class="btn_documents" disabled>Редагувати</button>
                            <button class="btn_documents" disabled>Видалити</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($categories as $cat): ?>
                    <div class="appointment-card" data-aos="fade-up" data-aos-duration="1000">
                        <img src="../../<?= htmlspecialchars($cat['image']) ?>" alt="Category Image"
                            style="width: 100%; height: auto; border-radius: 10px;">
                        <h4 class="card_title"><?= htmlspecialchars($cat['title']) ?></h4>

                        <div class="modal_btn_container">
                            <!-- Редагування буде пізніше -->
                            <button class="btn_documents" disabled>Редагувати</button>

                            <!-- Видалення -->
                            <a class="btn_documents" href="?delete_id=<?= $cat['id'] ?>"
                                onclick="return confirm('Ви впевнені, що хочете видалити категорію?')">Видалити</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<script src="../js/admin.js"></script>
<?php require "../user-footer.php"; ?>