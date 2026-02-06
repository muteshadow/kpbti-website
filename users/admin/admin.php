<?php
include_once "../../assets/config.php";

if (session_status() === PHP_SESSION_NONE) session_start();

if (DEMO_MODE) {
    $_SESSION['role'] = 'admin';
    $_SESSION['user_id'] = 999;
} else {
    // Безпека для реальної версії
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../../index.php");
        exit();
    }
}

$parentPageTitle = 'Вихід';
$parentPageUrl = '../logout.php';
$pageTitle = "Панель адміністратора";

require "../user-header.php";
preventCaching();

// 5. ЛОГІКА ДАНИХ (фільтри)
$surname = isset($_GET['surname']) ? trim($_GET['surname']) : '';
$role = isset($_GET['role']) ? trim($_GET['role']) : '';

if (DEMO_MODE) {
    require_once '../js/demo-data/users.php';
    $users = array_filter($demoUsers, function ($u) use ($surname, $role) {
        $surnameOk = $surname === '' || stripos($u['surname'], $surname) !== false;
        $roleOk = $role === '' || $u['role'] === $role;
        return $surnameOk && $roleOk;
    });
} else {
    $users = getAllUsers($conn, $surname, $role);
}

require "admin-modal-window.php";
?>

<section class="section section-short">
    <div class="container">
        <h3 class="section_title title_center" data-aos="fade-up" data-aos-duration="1000">Управління користувачами</h3>

        <!-- Форма для пошуку за прізвищем та роллю -->
        <div class="search_container">
            <div data-aos="fade-up" data-aos-duration="1000">
                <button class="btn" onclick="openModal('addUserModal')">Додати користувача</button>
            </div>

            <form method="GET" action="admin.php">
                <div class="search-block" data-aos="fade-up" data-aos-duration="1000">
                    <input type="text" name="surname" placeholder="Пошук за прізвищем"
                        value="<?= htmlspecialchars($surname) ?>">
                    <button class="search-button" type="submit" class="search-btn">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>

            <form method="GET" action="admin.php">
                <div class="search-block" data-aos="fade-up" data-aos-duration="1000">
                    <select name="role">
                        <option value="">Вибір ролі</option>
                        <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>Користувач</option>
                        <option value="worker" <?= $role === 'worker' ? 'selected' : '' ?>>Працівник</option>
                        <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Адміністратор</option>
                    </select>
                    <button class="search-button" type="submit">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                </div>
            </form>

            <div data-aos="fade-up" data-aos-duration="1000">
                <a href="admin.php" class="btn_documents reset-btn">Усі користувачі</a>
                <!-- Кнопка для скидання фільтрів -->
            </div>


        </div>

        <div class="inner_section card_container">
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <div class="card" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-circle">
                            <?= htmlspecialchars($user['role']) ?>
                        </div>
                        <h4 class="card_title">
                            <?= htmlspecialchars($user['surname'] . " " . $user['name'] . " " . $user['patronymic']) ?>
                        </h4>
                        <div class="card_job"><?= htmlspecialchars($user['email']) ?></div>
                        <div class="modal_btn_container">
                            <button class="btn_documents"
                                onclick="openEditUserModal(<?= $user['id'] ?>, '<?= addslashes($user['name']) ?>', '<?= addslashes($user['surname']) ?>', '<?= addslashes($user['patronymic']) ?>', '<?= addslashes($user['email']) ?>', '<?= addslashes($user['role']) ?>')">Редагувати</button>
                            <button class="btn_documents" onclick="deleteUser(<?= $user['id'] ?>)">Видалити</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Користувачів не знайдено</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- JS -->
<script src="../js/admin.js"></script>

<?php require "../user-footer.php"; ?>