<?php

// news
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

// about
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

// documents
// Документы по types_id
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

// services
// Отримання категорій
function getServices($conn)
{
    $sql = "SELECT c.id, c.title AS category_name, c.image, 
                   GROUP_CONCAT(DISTINCT isv.service_name ORDER BY isv.service_name SEPARATOR ', ') AS services
            FROM categories c
            JOIN individual_services isv ON c.id = isv.service_id
            GROUP BY c.id, c.title, c.image";

    $result = $conn->query($sql);

    $services = [];
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }

    return $services;
}

// Отримання необхідних документів
function getServicesByCategory($conn)
{
    $sql = "SELECT c.id AS category_id, c.title AS category_title, c.image, 
                   s.service_id, s.service_name
            FROM categories c
            JOIN individual_services s ON c.id = s.service_id
            GROUP BY s.service_id";

    $result = $conn->query($sql);
    $services = [];

    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }

    return $services;
}