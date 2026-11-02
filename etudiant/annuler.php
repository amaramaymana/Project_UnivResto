<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'etudiant'){
    header("Location: ../auth/login.php");
    exit();
}

$user = $_SESSION['id'];
$meal_id = intval($_GET['id']);

$check = $pdo->prepare("SELECT * FROM confirmations WHERE user_id=? AND meal_id=?");
$check->execute([$user,$meal_id]);

if($check->rowCount() > 0){

    $conf = $check->fetch();

    $now = new DateTime();
    $confirm_time = new DateTime($conf['created_at']);

    $limit = clone $confirm_time;
    $limit->modify('+30 minutes');

    if($now <= $limit){
        $pdo->prepare("DELETE FROM confirmations WHERE user_id=? AND meal_id=?")
            ->execute([$user,$meal_id]);

        header("Location: dashboard.php?msg=cancelled");
    } else {
        header("Location: dashboard.php?msg=error");
    }

} else {
    header("Location: dashboard.php");
}

exit();
?>