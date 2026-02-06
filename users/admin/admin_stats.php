<?php
$parentPageTitle = 'Вихід';
$parentPageUrl = '../logout.php';
$pageTitle = "Панель адміністратора";

require "../user-header.php";
preventCaching();

if (DEMO_MODE) {
    $_SESSION['role'] = 'admin';
    
    require '../../assets/demo-data/stats.php';

    $statuses = $demoStatuses;
} else {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../../index.php");
        exit();
    }

    // Отримання статистики
    $totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users WHERE role = 'user'"))['count'];
    $totalWorkers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users WHERE role = 'worker'"))['count'];
    $completedOrders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointments WHERE status = 'завершено'"))['count'];

    $statuses = [
        'в процесі' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointments WHERE status = 'в процесі'"))['count'],
        'скасовано' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM appointments WHERE status = 'скасовано'"))['count'],
        'завершено' => $completedOrders
    ];
}
?>

<!-- Diagram -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../../js/chart.js"></script>

<!-- Статистика замовлень -->
<div id="stats" class="stats_section" data-aos="fade-up" data-aos-duration="1000">
    <div class="stat" style="display: block">
        <canvas id="orderStatusChart"></canvas>
    </div>

    <div class="stat">
        <div class="number" data-target="<?= $totalUsers ?>">0</div>
        <div class="description">Зареєстрованих користувачів нашого сайту</div>
    </div>
    <div class="stat">
        <div class="number" data-target="<?= $completedOrders ?>">0</div>
        <div class="description">Клієнтів вже отримали бажані послуги</div>
    </div>
    <div class="stat">
        <div class="number" data-target="<?= $totalWorkers ?>">0</div>
        <div class="description">Працівників комунального підприємства</div>
    </div>
</div>

<script>
    // Дані з PHP
    var orderData = [<?= $statuses['в процесі'] ?>, <?= $statuses['скасовано'] ?>, <?= $statuses['завершено'] ?>];

    // Виклик функції
    renderOrderStatusChart('orderStatusChart', orderData);
</script>

<?php require "../user-footer.php"; ?>