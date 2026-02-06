<?php
session_start();
ini_set('display_errors', 1); // для налагодження (вимкнеш на проді)
ini_set('log_errors', 1);
error_reporting(E_ALL);

include_once "../../assets/config.php";
header('Content-Type: application/json');

// Перевірка запиту
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Неправильний метод запиту."]);
    exit();
}

// Отримання ID користувача
$user_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

// Перевірка, чи існує користувач
$user_query = "SELECT id FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $user_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo json_encode(["success" => false, "message" => "Користувача не знайдено."]);
    exit();
}

// Захист від видалення свого облікового запису
if ($user_id == $_SESSION['user_id']) {
    echo json_encode(["success" => false, "message" => "Ви не можете видалити свій власний акаунт."]);
    exit();
}

// Видалення документів консультацій
$deleteDocs = "
    DELETE FROM appointment_documents 
    WHERE appointment_id IN (
        SELECT id FROM appointments WHERE user_id = ?
    )";
$stmt = mysqli_prepare($conn, $deleteDocs);
mysqli_stmt_bind_param($stmt, "i", $user_id);
if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(["success" => false, "message" => "Помилка при видаленні документів: " . mysqli_error($conn)]);
    exit();
}

// Видалення консультацій
$deleteAppointments = "DELETE FROM appointments WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $deleteAppointments);
mysqli_stmt_bind_param($stmt, "i", $user_id);
if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(["success" => false, "message" => "Помилка при видаленні консультацій: " . mysqli_error($conn)]);
    exit();
}

// Видалення користувача
$deleteUser = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $deleteUser);
mysqli_stmt_bind_param($stmt, "i", $user_id);
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["success" => true, "message" => "Користувача успішно видалено."]);
} else {
    echo json_encode(["success" => false, "message" => "Помилка при видаленні користувача: " . mysqli_error($conn)]);
}
