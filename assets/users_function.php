<?php

// ==============================
// Користувач user-dashboard.php
// ==============================

// Заборона кешування сторінки
function preventCaching()
{
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

// Отримання ПІБ користувача з бази даних
function getUserFullName($conn, $user_id)
{
    $surname = $name = $patronymic = ''; // Ініціалізація змінних

    $stmt = $conn->prepare("SELECT surname, name, patronymic FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($surname, $name, $patronymic);
    $stmt->fetch();
    $stmt->close();

    return [$surname, $name, $patronymic];
}

// Отримання календаря
function getCalendarData($month, $year)
{
    // Встановлюємо поточний місяць та рік, якщо параметри не передані
    $month = $month !== null ? intval($month) : date('m');
    $year = $year !== null ? intval($year) : date('Y');

    // Додаємо перевірку діапазону місяця
    if ($month < 1 || $month > 12) {
        $month = date('m');
    }

    // Додаємо перевірку року
    if ($year < 1970 || $year > 2100) {
        $year = date('Y');
    }

    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $firstDayOfMonth = date('N', strtotime("$year-$month-01"));
    $lastDayOfMonth = date('N', strtotime("$year-$month-$daysInMonth"));

    $prevMonth = $month == 1 ? 12 : $month - 1;
    $nextMonth = $month == 12 ? 1 : $month + 1;
    $prevYear = $month == 1 ? $year - 1 : $year;
    $nextYear = $month == 12 ? $year + 1 : $year;

    return [
        'daysInMonth' => $daysInMonth,
        'firstDayOfMonth' => $firstDayOfMonth,
        'lastDayOfMonth' => $lastDayOfMonth,
        'prevMonth' => $prevMonth,
        'nextMonth' => $nextMonth,
        'prevYear' => $prevYear,
        'nextYear' => $nextYear,
        'currentDate' => date('Y-m-d'),
    ];
}

// Отримання інформації про поточний місяць і рік
function getCurrentMonthYear()
{
    $month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
    return [$month, $year];
}

// Отримання дат місяця
function getMonthDetails($month, $year)
{
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $firstDayOfMonth = date('N', strtotime("$year-$month-01"));
    $lastDayOfMonth = date('N', strtotime("$year-$month-$daysInMonth"));

    $nextMonth = $month == 12 ? 1 : $month + 1;
    $prevMonth = $month == 1 ? 12 : $month - 1;
    $nextYear = $month == 12 ? $year + 1 : $year;
    $prevYear = $month == 1 ? $year - 1 : $year;

    return [$daysInMonth, $firstDayOfMonth, $lastDayOfMonth, $nextMonth, $prevMonth, $nextYear, $prevYear];
}

// Отримання замовлень користувача
function getUserAppointments($conn, $user_id)
{
    $stmt = $conn->prepare("
        SELECT 
            a.id, a.date, a.time, a.person_type, a.status,
            COALESCE(s.service_name, ls.service_name) AS service_name,
            c.title AS category_name,
            GROUP_CONCAT(d.title SEPARATOR ', ') AS documents
        FROM appointments a
        LEFT JOIN individual_services s ON a.service_id = s.id AND a.person_type = 'individual'
        LEFT JOIN legal_services ls ON a.service_id = ls.id AND a.person_type = 'legal'
        LEFT JOIN categories c ON s.category_id = c.id OR ls.category_id = c.id
        LEFT JOIN appointment_documents ad ON a.id = ad.appointment_id
        LEFT JOIN documents d ON FIND_IN_SET(d.id, ad.document_ids)
        WHERE a.user_id = ?
        GROUP BY a.id
        ORDER BY a.date DESC, a.time ASC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// ==============================
// Користувач worker-dashboard.php
// ==============================

// Функція для отримання користувачів з роллю 'user' та їх замовлень
function getUsersAndAppointments($conn, $surname = '', $status = '', $date_exact = '')
{
    $params = [];
    $sql_users = "SELECT id, name, surname, patronymic, email FROM users WHERE role = 'user'";

    if (!empty($surname)) {
        $sql_users .= " AND surname LIKE ?";
        $params[] = '%' . $surname . '%';
    }

    $stmt = mysqli_prepare($conn, $sql_users);

    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, str_repeat("s", count($params)), ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result_users = mysqli_stmt_get_result($stmt);

    $users_data = [];

    if ($result_users && mysqli_num_rows($result_users) > 0) {
        while ($user = mysqli_fetch_assoc($result_users)) {
            $user_id = $user['id'];
            $user_name = htmlspecialchars_decode($user['surname'] . " " . $user['name'] . " " . $user['patronymic']);
            $user_email = htmlspecialchars($user['email']);

            $sql_appointments = "SELECT a.id, a.date, a.time, a.person_type, a.status,
                                        COALESCE(s.service_name, ls.service_name) AS service_name,
                                        COALESCE(c.title, lc.title) AS category_name,
                                        GROUP_CONCAT(d.title SEPARATOR ', ') AS documents
                                 FROM appointments a
                                 LEFT JOIN individual_services s ON a.service_id = s.id AND a.person_type = 'individual'
                                 LEFT JOIN legal_services ls ON a.service_id = ls.id AND a.person_type = 'legal'
                                 LEFT JOIN categories c ON s.category_id = c.id
                                 LEFT JOIN categories lc ON ls.category_id = lc.id
                                 LEFT JOIN appointment_documents ad ON a.id = ad.appointment_id
                                 LEFT JOIN documents d ON FIND_IN_SET(d.id, ad.document_ids)
                                 WHERE a.user_id = ?";

            $params_appointments = [$user_id];
            $types = "i";

            if (!empty($status)) {
                $sql_appointments .= " AND a.status = ?";
                $params_appointments[] = $status;
                $types .= "s";
            }

            if (!empty($date_exact)) {
                $sql_appointments .= " AND a.date = ?";
                $params_appointments[] = $date_exact;
                $types .= "s";
            }

            $sql_appointments .= " GROUP BY a.id ORDER BY a.date DESC, a.time ASC";

            $stmt_appointments = mysqli_prepare($conn, $sql_appointments);
            mysqli_stmt_bind_param($stmt_appointments, $types, ...$params_appointments);
            mysqli_stmt_execute($stmt_appointments);
            $result_appointments = mysqli_stmt_get_result($stmt_appointments);

            $appointments = [];
            if ($result_appointments && mysqli_num_rows($result_appointments) > 0) {
                while ($appointment = mysqli_fetch_assoc($result_appointments)) {
                    $appointments[] = [
                        'id' => $appointment['id'],
                        'date' => htmlspecialchars($appointment['date']),
                        'time' => htmlspecialchars($appointment['time']),
                        'person_type' => htmlspecialchars($appointment['person_type']),
                        'status' => htmlspecialchars($appointment['status']),
                        'service_name' => htmlspecialchars($appointment['service_name']),
                        'category_name' => htmlspecialchars($appointment['category_name']),
                        'documents' => htmlspecialchars($appointment['documents'])
                    ];
                }
            }

            $users_data[] = [
                'id' => $user_id,
                'name' => $user_name,
                'email' => $user_email,
                'appointments' => $appointments
            ];
        }
    }

    return $users_data;
}

// Функція для оновлення статусу замовлення
function updateAppointmentStatus($conn, $appointment_id, $new_status)
{
    $allowed_statuses = ["в процесі", "скасовано", "завершено"];

    if (!in_array($new_status, $allowed_statuses)) {
        return "Недійсний статус.";
    }

    $sql = "UPDATE appointments SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $new_status, $appointment_id);

    if (mysqli_stmt_execute($stmt)) {
        return "Статус оновлено.";
    } else {
        return "Помилка при оновленні статусу.";
    }
}

// Замовлення за датою
function getAppointments($conn, $date = null)
{
    if ($date) {
        $query = "SELECT * FROM appointments WHERE date = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $query = "SELECT * FROM appointments";
        $result = $conn->query($query);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

// ==============================
//       Адмін admin.php
// ==============================

// Отримання користувачів
function getAllUsers($conn, $surname = '', $role = '')
{
    $params = [];
    $sql = "SELECT id, name, surname, patronymic, email, role FROM users";

    // Додаємо фільтрацію за прізвищем, якщо воно передано
    if (!empty($surname)) {
        $sql .= " WHERE surname LIKE ?";
        $params[] = '%' . $surname . '%';
    }

    // Додаємо фільтрацію за роллю, якщо вона передана
    if (!empty($role)) {
        // Якщо вже є умова WHERE (для прізвища), додаємо AND
        if (!empty($params)) {
            $sql .= " AND role = ?";
        } else {
            $sql .= " WHERE role = ?";
        }
        $params[] = $role;
    }

    // Підготовка та виконання запиту
    $stmt = mysqli_prepare($conn, $sql);

    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, str_repeat("s", count($params)), ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Збір даних користувачів
    $users = [];
    while ($user = mysqli_fetch_assoc($result)) {
        $users[] = $user;
    }

    return $users;
}
