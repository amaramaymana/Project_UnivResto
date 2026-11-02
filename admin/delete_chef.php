<?php
session_start();
include("../config/db.php");

if($_SESSION['role'] != 'admin') exit();

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM users WHERE id=? AND role='chef'");
$stmt->execute([$id]);

header("Location: chefs_list.php");
?>