<?php
session_start();
include("../config/db.php");

// 🔒 protection
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'chef'){
    header("Location: ../auth/login.php");
    exit();
}

//  vérifier id
if(!isset($_GET['id'])){
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("DELETE FROM meals WHERE id=?");
$stmt->execute([$id]);

header("Location: dashboard.php");
exit();
?>