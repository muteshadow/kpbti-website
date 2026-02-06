<?php

// ==============================
//      Статистика
// ==============================

// Функція для отримання статистики замовлень за статусами
function getOrderStatuses($conn)
{
    $query = "SELECT status, COUNT(*) as count FROM appointments GROUP BY status";
    $result = $conn->query($query);

    $statuses = [
        'в процесі' => 0,
        'скасовано' => 0,
        'завершено' => 0
    ];

    while ($row = $result->fetch_assoc()) {
        $statuses[$row['status']] = $row['count'];
    }

    return $statuses;
}

// Функція для отримання кількості користувачів з роллю user
function getTotalUsers($conn)
{
    $query = "SELECT COUNT(*) AS count FROM users WHERE role = 'user'";
    $result = $conn->query($query);
    return $result->fetch_assoc()['count'] ?? 0;
}

// Функція для отримання кількості завершених замовлень
function getCompletedOrders($conn)
{
    $query = "SELECT COUNT(*) AS count FROM appointments WHERE status = 'завершено'";
    $result = $conn->query($query);
    return $result->fetch_assoc()['count'] ?? 0;
}

// Функція для отримання кількості працівників
function getTotalWorkers($conn)
{
    $query = "SELECT COUNT(*) AS count FROM users WHERE role IN ('admin', 'worker')";
    $result = $conn->query($query);
    return $result->fetch_assoc()['count'] ?? 0;
}

// ==============================
//      Новини
// ==============================
function getNews($conn, $limit = null)
{
    $query = "SELECT id, title, date, text, image FROM news ORDER BY date DESC";
    if ($limit) {
        $query .= " LIMIT ?";
    }
    $stmt = $conn->prepare($query);
    if ($limit) {
        $stmt->bind_param("i", $limit);
    }
    $stmt->execute();
    return $stmt->get_result();
}

function getNewsById($conn, $id)
{
    $stmt = $conn->prepare("SELECT id, title, date, text, image FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// ==============================
//      Про нас
// ==============================
function getAboutByCategory($conn, $category = null)
{
    $query = "SELECT name, job, text, image, category FROM about";

    if ($category) {
        $query .= " WHERE category = ?";
    }
    $stmt = $conn->prepare($query);

    if ($category) {
        $stmt->bind_param("s", $category);
    }
    $stmt->execute();
    return $stmt->get_result();
}

// ==============================
//      Нормативні документи
// ==============================
function getDocumentsByType($conn, $type = null)
{
    $query = "SELECT title, link, type FROM regulatory_documents";

    if ($type) {
        $query .= " WHERE type = ?";
    }
    $stmt = $conn->prepare($query);

    if ($type) {
        $stmt->bind_param("s", $type);
    }
    $stmt->execute();
    return $stmt->get_result();
}

// ==============================
//      Послуги (категорії)
// ==============================

// Отримання послуг для фізичних осіб
function getIndividualServices($conn)
{
    $sql = "SELECT c.id, c.title AS category_name, c.image, 
                   GROUP_CONCAT(DISTINCT isv.service_name ORDER BY isv.service_name SEPARATOR ', ') AS services
            FROM categories c
            JOIN individual_services isv ON c.id = isv.category_id
            GROUP BY c.id, c.title, c.image";

    $result = $conn->query($sql);

    $services = [];
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }

    return $services;
}

// Отримання послуг для юридичних осіб
function getLegalServices($conn)
{
    $sql = "SELECT c.id, c.title AS category_name, c.image, 
                   GROUP_CONCAT(DISTINCT isv.service_name ORDER BY isv.service_name SEPARATOR ', ') AS services
            FROM categories c
            JOIN legal_services isv ON c.id = isv.category_id
            GROUP BY c.id, c.title, c.image";

    $result = $conn->query($sql);

    $services = [];
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }

    return $services;
}

// ==============================
//      Функції для service.php
// ==============================

// ==============================
//        Загальні функції
// ==============================

// Обрізання слів
function truncateWords($text, $limit = 3)
{
    $words = explode(' ', $text);
    if (count($words) <= $limit) {
        return $text;
    }
    return implode(' ', array_slice($words, 0, $limit)) . '...';
}

// Отримання URL та заголовка для різних типів осіб
function getParentPageInfo($personType)
{
    $info = [
        'url' => 'services.php',
        'title' => 'Послуги'
    ];

    if ($personType === 'individual') {
        $info = [
            'url' => 'individual.php',
            'title' => 'Фізична особа'
        ];
    } elseif ($personType === 'legal') {
        $info = [
            'url' => 'legal.php',
            'title' => 'Юридична особа'
        ];
    }

    return $info;
}

// ==============================
//      Функції для послуг
// ==============================

// Отримання ID послуги для фізичних осіб
function getServiceIdByNameIndividual($conn, $serviceName, $property)
{
    $categoryQuery = "SELECT id FROM categories WHERE title = ?";
    $stmt = $conn->prepare($categoryQuery);
    $stmt->bind_param("s", $property);
    $stmt->execute();
    $categoryResult = $stmt->get_result();
    $category = $categoryResult->fetch_assoc();
    $categoryId = $category['id'];

    $serviceQuery = "SELECT id, document_id FROM individual_services WHERE service_name = ? AND category_id = ?";
    $stmt = $conn->prepare($serviceQuery);
    $stmt->bind_param("si", $serviceName, $categoryId);
    $stmt->execute();
    $serviceResult = $stmt->get_result();
    return $serviceResult->fetch_assoc();
}

// Отримання ID послуги для юридичних осіб
function getServiceIdByNameLegal($conn, $serviceName, $property)
{
    $categoryQuery = "SELECT id FROM categories WHERE title = ?";
    $stmt = $conn->prepare($categoryQuery);
    $stmt->bind_param("s", $property);
    $stmt->execute();
    $categoryResult = $stmt->get_result();
    $category = $categoryResult->fetch_assoc();
    $categoryId = $category['id'];

    $serviceQuery = "SELECT id, document_id FROM legal_services WHERE service_name = ? AND category_id = ?";
    $stmt = $conn->prepare($serviceQuery);
    $stmt->bind_param("si", $serviceName, $categoryId);
    $stmt->execute();
    $serviceResult = $stmt->get_result();
    return $serviceResult->fetch_assoc();
}

// ==============================
//   Функції для документів
// ==============================

// Отримання документів за їх ID
function getDocumentsByIds($conn, $documentIds)
{
    $documentIdsArray = explode(",", $documentIds);
    $placeholders = implode(",", array_fill(0, count($documentIdsArray), "?"));
    $query = "SELECT title FROM documents WHERE id IN ($placeholders)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat("i", count($documentIdsArray)), ...$documentIdsArray);
    $stmt->execute();
    $result = $stmt->get_result();

    $documents = [];
    while ($row = $result->fetch_assoc()) {
        $documents[] = $row['title'];
    }

    return $documents;
}

// ==============================
//  Функції для категорій
// ==============================

// Отримання зображення категорії
function getCategoryImage($conn, $property)
{
    $query = "SELECT image FROM categories WHERE title = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $property);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['image'] ?? '';
}

?>