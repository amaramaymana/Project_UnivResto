<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'etudiant'){
    header("Location: ../auth/login.php");
    exit();
}

$user = $_SESSION['id'];
$meal_id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM meals WHERE id=?");
$stmt->execute([$meal_id]);
$meal = $stmt->fetch();

if(!$meal){
    header("Location: dashboard.php");
    exit();
}

/* CHECK DOUBLE CONFIRMATION */
$check = $pdo->prepare("SELECT * FROM confirmations WHERE user_id=? AND meal_id=?");
$check->execute([$user,$meal_id]);

if($check->rowCount() == 0){

    $pdo->prepare("INSERT INTO confirmations(user_id,meal_id,created_at) VALUES(?,?,NOW())")
        ->execute([$user,$meal_id]);

}

/* REDIRECT */
header("Location: dashboard.php?msg=confirmed");
exit();
?>