<?php
include_once "../../assets/config.php";

// Функція для додавання нового користувача
function addUser($conn, $name, $surname, $patronymic, $email, $password, $role)
{
    $sql = "INSERT INTO users (name, surname, patronymic, email, password, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $surname, $patronymic, $email, $hashedPassword, $role);
    return mysqli_stmt_execute($stmt);
}

// Встановлюємо заголовок для JSON-відповіді
header('Content-Type: application/json');

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $patronymic = trim($_POST['patronymic']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    // Перевірка заповнених полів
    if (empty($name) || empty($surname) || empty($email) || empty($password)) {
        $errors[] = "Будь ласка, заповніть всі обов'язкові поля.";
    }

    // Перевірка унікальності email
    $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $checkEmailQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $errors[] = "Користувач із таким email вже існує.";
    }

    // Додавання користувача
    if (empty($errors)) {
        if (addUser($conn, $name, $surname, $patronymic, $email, $password, $role)) {
            echo json_encode(['success' => true, 'message' => 'Користувача успішно додано.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Сталася помилка при додаванні користувача.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => implode(" ", $errors)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Неправильний метод запиту.']);
}
