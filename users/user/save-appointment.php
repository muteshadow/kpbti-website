<?php
session_start();
require_once "../../assets/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $person_type = $_POST['person_type'];
    $service_name = $_POST['service_name']; // Назва для пошуку ID
    
    // 1. Знаходимо ID послуги за назвою (так простіше з вашим JS)
    $table = ($person_type === 'individual') ? 'individual_services' : 'legal_services';
    $stmt = $conn->prepare("SELECT id, document_id FROM $table WHERE service_name = ? LIMIT 1");
    $stmt->bind_param("s", $service_name);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    
    if ($res) {
        $service_id = $res['id'];
        $doc_ids = $res['document_id']; // Беремо дефолтні документи з вашої структури

        // 2. Записуємо в appointments
        $stmt2 = $conn->prepare("INSERT INTO appointments (user_id, date, time, person_type, service_id, status) VALUES (?, ?, ?, ?, ?, 'в процесі')");
        $stmt2->bind_param("isssi", $user_id, $date, $time, $person_type, $service_id);
        
        if ($stmt2->execute()) {
            $new_id = $conn->insert_id;
            // 3. Записуємо документи в appointment_documents
            $stmt3 = $conn->prepare("INSERT INTO appointment_documents (appointment_id, document_ids) VALUES (?, ?)");
            $stmt3->bind_param("is", $new_id, $doc_ids);
            $stmt3->execute();
            
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conn->error]);
        }
    }
}