<?php

require_once "db_config.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

try {

    $stmt = $pdo->prepare("
        DELETE FROM employees
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    header("Location: index.php");
    exit;

} catch (PDOException $e) {

    die("Unable to delete employee.");
}