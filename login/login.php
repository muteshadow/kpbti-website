<?php
session_start();
include "../assets/config.php";

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);  // Додаємо отримання email
    $password = trim($_POST['password']); // Додаємо отримання пароля

    // Запит для перевірки по прізвищу та email
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE surname = ? AND email = ?");
    $stmt->bind_param("ss", $surname, $email);  // Перевіряємо за прізвищем та email
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $role); // Отримуємо роль
        $stmt->fetch();

        // Перевірка пароля
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;
            $_SESSION['user_surname'] = $surname;

            // Напрямок після входу в залежності від ролі
            $redirectPath = "index.php";

            if ($role === 'admin') {
                $redirectPath = "users/admin/admin.php";
            } elseif ($role === 'user') {
                $redirectPath = "users/user/user-dashboard.php";
            } elseif ($role === 'worker') {
                $redirectPath = "users/worker/worker-dashboard.php";
            }

            echo json_encode([
                "status" => "success",
                "message" => "Вхід успішний",
                "redirect" => $redirectPath
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Неправильний пароль"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Користувача не знайдено"]);
    }
}
?>