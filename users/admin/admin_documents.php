<?php
$parentPageTitle = 'Вихід';
$parentPageUrl = '../logout.php';
$pageTitle = "Управління документами";

require "../user-header.php";
preventCaching();

if (DEMO_MODE) {
    $_SESSION['role'] = 'admin';
    
    require '../../assets/demo-data/services.php';

    $documents = [];
    foreach ($demoServices['documents'] as $id => $title) {
        $documents[] = [
            'id' => $id,
            'title' => $title
        ];
    }
} else {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../../index.php");
        exit();
    }

    global $conn;

    // Додавання нового документа
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_document'])) {
        $title = trim(mysqli_real_escape_string($conn, $_POST['title']));
        if ($title !== '') {
            mysqli_query($conn, "INSERT INTO documents (title) VALUES ('$title')");
        }
        header("Location: admin_documents.php");
        exit();
    }

    // Видалення документа
    if (isset($_GET['delete_id'])) {
        $id = (int) $_GET['delete_id'];
        mysqli_query($conn, "DELETE FROM documents WHERE id = $id");
        header("Location: admin_documents.php");
        exit();
    }

    // Отримання всіх документів
    $result = mysqli_query($conn, "SELECT * FROM documents ORDER BY id DESC");
    $documents = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<section class="section section-short">
    <div class="container">
        <h3 class="section_title title_center" data-aos="fade-up" data-aos-duration="1000">Управління документами</h3>

        <!-- Форма додавання документа -->
        <form method="POST" style="margin-bottom: 30px;" data-aos="fade-up" data-aos-duration="1000">
            <input type="hidden" name="add_document" value="1">
            <label>Назва документа:</label><br>
            <textarea name="title" required rows="2" style="width: 100%;"></textarea><br><br>
            <button type="submit" class="btn_documents">Додати документ</button>
        </form>

        <!-- Виведення документів -->
        <div class="inner_section card_container">
            <?php foreach ($documents as $doc): ?>
                <div class="appointment-card" data-aos="fade-up" data-aos-duration="1000">
                    <div style="text-align: left;"><?= nl2br(htmlspecialchars($doc['title'])) ?></div>
                    <div class="modal_btn_container" style="margin-top: 10px;">
                        <button class="btn_documents" disabled>Редагувати</button>
                        <a class="btn_documents" href="?delete_id=<?= $doc['id'] ?>"
                            onclick="return confirm('Видалити документ?')">Видалити</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script src="../js/admin.js"></script>
<?php require "../user-footer.php"; ?>