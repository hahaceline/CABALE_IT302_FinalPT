<?php

header("Content-Type: application/json");

require_once "../db_config.php";

try {

    $stmt = $pdo->query("
        SELECT id, department_name
        FROM departments
        ORDER BY department_name ASC
    ");

    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $departments
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Unable to retrieve departments."
    ]);
}