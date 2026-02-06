<?php
include_once "../../assets/config.php";

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['user_id']);
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $patronymic = trim($_POST['patronymic']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    if ($id > 0 && $name !== '' && $surname !== '' && $email !== '' && $role !== '') {
        $sql = "UPDATE users SET name = ?, surname = ?, patronymic = ?, email = ?, role = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssi", $name, $surname, $patronymic, $email, $role, $id);

        if (mysqli_stmt_execute($stmt)) {
            $response['success'] = true;
            $response['message'] = "Дані користувача оновлено успішно!";
        } else {
            $response['message'] = "Помилка при оновленні даних користувача.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $response['message'] = "Заповніть усі обов'язкові поля.";
    }
}

// Повернення JSON відповіді
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>