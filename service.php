<?php
require "assets/header.php";

// Отримуємо параметри з URL
$property = isset($_GET['property']) ? $_GET['property'] : '';
$serviceName = isset($_GET['service']) ? $_GET['service'] : '';
$personType = isset($_GET['personType']) ? $_GET['personType'] : ''; // Тип особи (legal або individual)

// Функція для отримання ID послуги для фізичних осіб
function getServiceIdByNameIndividual($conn, $serviceName, $property)
{
    $categoryQuery = "SELECT id FROM categories WHERE title = ?";
    $stmt = $conn->prepare($categoryQuery);
    $stmt->bind_param("s", $property);
    $stmt->execute();
    $categoryResult = $stmt->get_result();
    $category = $categoryResult->fetch_assoc();
    $categoryId = $category['id'];

    $serviceQuery = "SELECT id, document_id FROM individual_services WHERE service_name = ? AND service_id = ?";
    $stmt = $conn->prepare($serviceQuery);
    $stmt->bind_param("si", $serviceName, $categoryId);
    $stmt->execute();
    $serviceResult = $stmt->get_result();
    return $serviceResult->fetch_assoc();
}

// Функція для отримання ID послуги для юридичних осіб
function getServiceIdByNameLegal($conn, $serviceName, $property)
{
    $categoryQuery = "SELECT id FROM categories WHERE title = ?";
    $stmt = $conn->prepare($categoryQuery);
    $stmt->bind_param("s", $property);
    $stmt->execute();
    $categoryResult = $stmt->get_result();
    $category = $categoryResult->fetch_assoc();
    $categoryId = $category['id'];

    $serviceQuery = "SELECT id, document_id FROM legal_services WHERE service_name = ? AND service_id = ?";
    $stmt = $conn->prepare($serviceQuery);
    $stmt->bind_param("si", $serviceName, $categoryId);
    $stmt->execute();
    $serviceResult = $stmt->get_result();
    return $serviceResult->fetch_assoc();
}

// Функція для отримання документів за їх ID
function getDocumentsByIds($conn, $documentIds)
{
    $documentIdsArray = explode(",", $documentIds); // Розділяємо ID документів
    $placeholders = implode(",", array_fill(0, count($documentIdsArray), "?")); // Створюємо місця для запиту
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

// Вибір функції в залежності від типу особи
if ($personType == 'individual') {
    $service = getServiceIdByNameIndividual($conn, $serviceName, $property);
} else if ($personType == 'legal') {
    $service = getServiceIdByNameLegal($conn, $serviceName, $property);
} else {
    // Якщо тип особи не вказаний або невірний
    $service = null;
}

// Якщо ID послуги знайдено
if ($service) {
    // Отримуємо документи для цієї послуги
    $documents = getDocumentsByIds($conn, $service['document_id']);
} else {
    $documents = [];
}
?>

<div class="service_details">
    <h2>Необхідні документи для обраної послуги</h2>
    <?php if (count($documents) > 0): ?>
        <ul>
            <?php foreach ($documents as $document): ?>
                <li><?= htmlspecialchars($document) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Документи для цієї послуги не знайдено.</p>
    <?php endif; ?>
</div>

<?php
require "assets/footer.php";
?>