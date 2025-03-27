<?php
include "../assets/config.php";

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $surname = trim($_POST['surname']);
    $name = trim($_POST['name']);
    $patronymic = trim($_POST['patronymic']);
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хешування пароля

    if (empty($surname) || empty($name) || empty($patronymic) || empty($hashed_password)) {
        echo json_encode(["status" => "error", "message" => "Всі поля обов'язкові!"]);
        exit;
    }

    // Перевірка наявності такого ж імені користувача
    $checkQuery = $conn->prepare("SELECT id FROM users WHERE surname = ? AND name = ? AND patronymic = ?");
    $checkQuery->bind_param("sss", $surname, $name, $patronymic);
    $checkQuery->execute();
    $checkQuery->store_result();

    if ($checkQuery->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Користувач з таким ФІО вже існує"]);
        exit;
    }

    // Додаємо нового користувача
    $stmt = $conn->prepare("INSERT INTO users (surname, name, patronymic, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $surname, $name, $patronymic, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Реєстрація успішна"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Помилка реєстрації"]);
    }
}