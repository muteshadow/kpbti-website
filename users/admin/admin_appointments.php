<?php
$parentPageTitle = 'Вихід';
$parentPageUrl = '../logout.php';
$pageTitle = "Панель адміністратора";

require "../user-header.php";
preventCaching();

if (DEMO_MODE) {
    $_SESSION['role'] = 'admin';
    
    require '../../assets/demo-data/user_appointments.php';

    $surname = $_GET['surname'] ?? '';
    $status  = $_GET['status'] ?? '';
    $date    = $_GET['date'] ?? '';

    $statuses = ['в процесі', 'завершено', 'скасовано'];

    // demo до "admin-формату"
    $appointments = array_map(function ($a) {
        $parts = explode(' ', $a['user'], 2);

        return [
            'id' => $a['id'],
            'surname' => $parts[0] ?? '',
            'name' => $parts[1] ?? '',
            'patronymic' => '',
            'email' => $a['user_email'],
            'date' => $a['date'],
            'time' => substr($a['time'], 0, 2),
            'person_type' => $a['person_type'],
            'status' => $a['status'],
        ];
    }, $demoUserAppointments);

    // фільтри
    $filteredAppointments = array_filter($appointments, function ($a) use ($surname, $status, $date) {
        if ($surname && stripos($a['surname'], $surname) === false) return false;
        if ($status && $a['status'] !== $status) return false;
        if ($date && $a['date'] !== $date) return false;
        return true;
    });
} else {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../../index.php");
        exit();
    }

    $surname = $_GET['surname'] ?? '';
    $status = $_GET['status'] ?? '';
    $date = $_GET['date'] ?? '';

    $query = "SELECT a.*, u.surname, u.name, u.patronymic 
            FROM appointments a 
            JOIN users u ON a.user_id = u.id 
            WHERE 1";

    if ($surname !== '') {
        $query .= " AND u.surname LIKE '%" . mysqli_real_escape_string($conn, $surname) . "%'";
    }
    if ($status !== '') {
        $query .= " AND a.status = '" . mysqli_real_escape_string($conn, $status) . "'";
    }
    if ($date !== '') {
        $query .= " AND a.date = '" . mysqli_real_escape_string($conn, $date) . "'";
    }

    $query .= " ORDER BY a.date DESC, a.time ASC";

    $result = mysqli_query($conn, $query);
    $statuses = ['в процесі', 'завершено', 'скасовано'];
}
?>

<section class="section section-short">
    <div class="container">
        <h3 class="section_title title_center" data-aos="fade-up" data-aos-duration="1000">Управління замовленнями</h3>

        <div class="search_container">
            <!-- Прізвище -->
            <form method="GET" action="admin_appointments.php">
                <div class="search-block" data-aos="fade-up" data-aos-duration="1000">
                    <input type="text" name="surname" placeholder="Пошук за прізвищем"
                        value="<?= htmlspecialchars($surname) ?>">
                    <button class="search-button"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>

            <!-- Статус -->
            <form method="GET" action="admin_appointments.php">
                <div class="search-block" data-aos="fade-up" data-aos-duration="1000">
                    <select name="status">
                        <option value="">Всі статуси</option>
                        <?php foreach ($statuses as $s): ?>
                            <option value="<?= $s ?>" <?= $s === $status ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="search-button"><i class="fa-solid fa-filter"></i></button>
                </div>
            </form>

            <!-- Дата -->
            <form method="GET" action="admin_appointments.php">
                <div class="search-block" data-aos="fade-up" data-aos-duration="1000">
                    <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
                    <button class="search-button"><i class="fa-solid fa-calendar-days"></i></button>
                </div>
            </form>

            <!-- Скидання -->
            <div data-aos="fade-up" data-aos-duration="1000">
                <a href="admin_appointments.php" class="btn_documents reset-btn">Скинути фільтри</a>
            </div>
        </div>

        <div class="inner_section card_container">
            <?php if (DEMO_MODE): ?>

                <?php if (!empty($filteredAppointments)): ?>
                    <?php foreach ($filteredAppointments as $row): ?>
                        <div class="appointment-card" data-id="<?= $row['id'] ?>" data-aos="fade-up" data-aos-duration="1000">
                            <h4 class="card_title">
                                <?= htmlspecialchars("{$row['surname']} {$row['name']} {$row['patronymic']}") ?>
                            </h4>
                            <div class="card_job">
                                <?= htmlspecialchars($row['date']) ?> о <?= htmlspecialchars($row['time']) ?>:00<br>
                                Тип особи: <?= htmlspecialchars($row['person_type']) ?>
                            </div>

                            <div class="modal_btn_container">
                                <select class="btn_documents change-status">
                                    <?php foreach ($statuses as $s): ?>
                                        <option value="<?= $s ?>" <?= $row['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn_documents delete-appointment">Видалити</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Замовлень не знайдено</p>
                <?php endif; ?>

            <?php else: ?>

                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="appointment-card" data-id="<?= $row['id'] ?>" data-aos="fade-up" data-aos-duration="1000">
                            <h4 class="card_title">
                                <?= htmlspecialchars("{$row['surname']} {$row['name']} {$row['patronymic']}") ?>
                            </h4>
                            <div class="card_job">
                                <?= htmlspecialchars($row['date']) ?> о <?= htmlspecialchars($row['time']) ?>:00<br>
                                Тип особи: <?= htmlspecialchars($row['person_type']) ?>
                            </div>

                            <div class="modal_btn_container">
                                <select class="btn_documents change-status">
                                    <?php foreach ($statuses as $s): ?>
                                        <option value="<?= $s ?>" <?= $row['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn_documents delete-appointment">Видалити</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Замовлень не знайдено</p>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
</section>

<!-- JS -->
<script src="../js/admin.js"></script>

<?php require "../user-footer.php"; ?>