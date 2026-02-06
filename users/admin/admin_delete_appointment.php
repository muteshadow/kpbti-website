<?php
require_once "../../assets/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    mysqli_query($conn, "DELETE FROM appointments WHERE id = $id");
    echo "DELETED";
}