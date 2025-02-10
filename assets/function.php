<?php

// news
function getNews($conn, $limit = null) {
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

function getNewsById($conn, $id) {
    $stmt = $conn->prepare("SELECT id, title, date, text, image FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// about
function getAboutByCategory($conn, $category = null) {
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

// Документы по types_id
function getDocumentsByType($conn, $type_id) {
    $sql = "SELECT * FROM documents WHERE types_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $type_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $documents = [];
    while ($row = $result->fetch_assoc()) {
        $documents[] = $row;
    }

    return $documents;
}
