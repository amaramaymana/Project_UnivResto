<?php
include("../config/db.php");

$last = $_GET['last'];

$stmt = $pdo->prepare("
SELECT * FROM meals 
WHERE created_at > ?
ORDER BY created_at DESC
");

$stmt->execute([$last]);

echo json_encode($stmt->fetchAll());