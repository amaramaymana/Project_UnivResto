<?php
session_start();
include("../config/db.php");

$id = $_GET['id'];

$newPass = "chef123"; // default
$hash = password_hash($newPass, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password=? WHERE id=?");
$stmt->execute([$hash,$id]);

echo "✅ Mot de passe reset: chef123 <br>";
echo "<a href='chefs_list.php'>Retour</a>";