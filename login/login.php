<?php 
session_start();
include "../assets/config.php";

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $surname = trim($_POST['surname']);
    $password = trim($_POST['password']); // Додаємо отримання пароля

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE surname = ?");
    $stmt->bind_param("s", $surname);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $role); // Отримуємо роль
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;
            $_SESSION['user_surname'] = $surname;

            echo json_encode([
                "status" => "success",
                "message" => "Вхід успішний",
                "redirect" => ($role === 'admin') ? "admin.php" : "index.php"
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Неправильний пароль"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Користувача не знайдено"]);
    }
}