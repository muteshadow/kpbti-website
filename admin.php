<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

require "assets/header.php";
$parentPageTitle = 'Вихід';
$parentPageUrl = 'logout.php';
$pageTitle = "Панель адміністратора";

require "assets/intro-short.php";
?>

<?php
require "assets/footer.php";
?>