<?php
$parentPageTitle = 'Вихід';
$parentPageUrl = '../logout.php';
$pageTitle = "Панель адміністратора";

require "../user-header.php";
preventCaching();

if (DEMO_MODE) {
    $_SESSION['role'] = 'admin';
    
    require '../../assets/demo-data/services.php';

    // --- Категорії ---
    $categories = [];
    $catMap = [];
    $idCounter = 1;

    foreach (['individual', 'legal'] as $personType) {
        if (!isset($demoServices[$personType])) continue;
        foreach ($demoServices[$personType] as $service) {
            $catName = $service['category_name'];
            if (!isset($catMap[$catName])) {
                $categories[] = ['id' => $idCounter, 'title' => $catName];
                $catMap[$catName] = $idCounter;
                $idCounter++;
            }
        }
    }

    // --- Документи ---
    $documents = [];
    foreach ($demoServices['documents'] as $docId => $docTitle) {
        $documents[] = ['id' => $docId, 'title' => $docTitle];
    }

    // --- Послуги ---
    $individualServices = [];
    $legalServices = [];
    $serviceId = 1;

    foreach ($demoServices['individual'] ?? [] as $s) {
        $individualServices[] = [
            'id' => $serviceId++,
            'service_name' => $s['service_name'],
            'category_id' => $catMap[$s['category_name']] ?? 0,
            'document_id' => implode(',', $s['document_ids'] ?? [])
        ];
    }

    foreach ($demoServices['legal'] ?? [] as $s) {
        $legalServices[] = [
            'id' => $serviceId++,
            'service_name' => $s['service_name'],
            'category_id' => $catMap[$s['category_name']] ?? 0,
            'document_id' => implode(',', $s['document_ids'] ?? [])
        ];
    }

    // --- Функції для DEMO ---
    function getCategoryName($categories, $category_id) {
        foreach ($categories as $category) {
            if ($category['id'] == $category_id) return $category['title'];
        }
        return "Невідомо";
    }

    function getDocumentNames($documents, $docIds) {
        if (!$docIds) return '';
        $docIdsArray = array_map('trim', explode(',', $docIds));
        $names = [];
        foreach ($documents as $doc) {
            if (in_array($doc['id'], $docIdsArray)) {
                $names[] = "• " . htmlspecialchars($doc['title']);
            }
        }
        return implode("<br>", $names);
    }
} else {
    // Підключення до БД (припускаю, що $conn є у user-header.php або config)
    global $conn;

    // Функції
    function getAllIndividualServices($conn)
    {
        $res = mysqli_query($conn, "SELECT * FROM individual_services");
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    function getAllLegalServices($conn)
    {
        $res = mysqli_query($conn, "SELECT * FROM legal_services");
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    function getAllCategories($conn)
    {
        $res = mysqli_query($conn, "SELECT * FROM categories");
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    function getAllDocuments($conn)
    {
        $res = mysqli_query($conn, "SELECT * FROM documents");
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    function getCategoryName($categories, $category_id)
    {
        foreach ($categories as $category) {
            if ($category['id'] == $category_id)
                return $category['title'];
        }
        return "Невідомо";
    }

    function getDocumentNames($documents, $docIds)
    {
        if (!$docIds)
            return '';
        $docIdsArray = array_map('trim', explode(',', $docIds));
        $names = [];
        foreach ($documents as $doc) {
            if (in_array($doc['id'], $docIdsArray)) {
                $names[] = "• " . htmlspecialchars($doc['title']);
            }
        }
        return implode("<br>", $names);
    }

    // Дані для відображення
    $individualServices = getAllIndividualServices($conn);
    $legalServices = getAllLegalServices($conn);
    $categories = getAllCategories($conn);
    $documents = getAllDocuments($conn);
}
?>

<section class="section section-short">
    <div class="container">
        <h3 class="section_title title_center" data-aos="fade-up" data-aos-duration="1000">Управління послугами</h3>

        <div data-aos="fade-up" data-aos-duration="1000" style="text-align: center; margin-bottom: 20px;">
            <button class="btn" onclick="openAddServiceModal()">Додати послугу</button>
        </div>

        <h4 class="section_subtitle" data-aos="fade-up">Фізичні особи</h4>
        <div class="inner_section card_container">
            <?php foreach ($individualServices as $service): ?>
                <div class="appointment-card" data-aos="fade-up" data-aos-duration="1000">
                    <h4 class="card_title"><?= htmlspecialchars($service['service_name']) ?>
                    </h4>
                    <div class="card_job"><?= htmlspecialchars(getCategoryName($categories, $service['category_id'])) ?>
                    </div>
                    <div class="card_job" style="text-align: justify;">
                        <?= getDocumentNames($documents, $service['document_id']) ?>
                    </div>
                    <div class="modal_btn_container">
                        <button class="btn_documents"
                            onclick="editService(<?= $service['id'] ?>, 'individual')">Редагувати</button>
                        <button class="btn_documents"
                            onclick="deleteService(<?= $service['id'] ?>, 'individual')">Видалити</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h4 class="section_subtitle" data-aos="fade-up" data-aos-duration="1000">Юридичні особи</h4>
        <div class="inner_section card_container">
            <?php foreach ($legalServices as $service): ?>
                <div class="appointment-card" data-aos="fade-up" data-aos-duration="1000">
                    <h4 class="card_title"><?= htmlspecialchars($service['service_name']) ?></h4>
                    <div class="card_job"><?= htmlspecialchars(getCategoryName($categories, $service['category_id'])) ?>
                    </div>
                    <div class="card_job" style="text-align: justify;">
                        <?= getDocumentNames($documents, $service['document_id']) ?></div>
                    <div class="modal_btn_container">
                        <button class="btn_documents"
                            onclick="editService(<?= $service['id'] ?>, 'legal')">Редагувати</button>
                        <button class="btn_documents"
                            onclick="deleteService(<?= $service['id'] ?>, 'legal')">Видалити</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Модальне вікно для додавання/редагування (порожнє, допрацюємо пізніше) -->
<div id="serviceModal" class="modal" style="display:none;">
    <div class="modal_content">
        <span class="close" onclick="closeServiceModal()">&times;</span>
        <h3 id="modalTitle">Додати послугу</h3>

        <form id="serviceForm">
            <input type="hidden" name="id" id="serviceId" value="">
            <label>Тип особи:</label><br>
            <select name="person_type" id="personType" required>
                <option value="individual">Фізична особа</option>
                <option value="legal">Юридична особа</option>
            </select><br><br>

            <label>Категорія:</label><br>
            <select name="category_id" id="categoryId" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['title']) ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Назва послуги:</label><br>
            <input type="text" name="name" id="serviceName" required><br><br>

            <label>Документи (можна вибрати кілька):</label><br>
            <?php foreach ($documents as $doc): ?>
                <input type="checkbox" name="document_ids[]" value="<?= $doc['id'] ?>" id="doc_<?= $doc['id'] ?>">
                <label for="doc_<?= $doc['id'] ?>"><?= htmlspecialchars($doc['title']) ?></label>
            <?php endforeach; ?><br>

            <button type="submit">Зберегти</button>
        </form>
    </div>
</div>

<script src="../js/admin.js"></script>

<?php require "../user-footer.php"; ?>