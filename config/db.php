<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$pdo = new PDO("mysql:host=localhost;dbname=resto_univ", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>