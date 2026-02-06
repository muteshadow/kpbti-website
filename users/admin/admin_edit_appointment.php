<?php
require_once "../../assets/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn, "UPDATE appointments SET status = '$status' WHERE id = $id");
    echo "OK";
}