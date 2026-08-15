<?php

header("Content-Type: application/json");

require_once "../db_config.php";

try {

    $stmt = $pdo->query("
        SELECT
            employee_id,
            CONCAT(first_name, ' ', last_name) AS employee_name,
            position
        FROM employees
        ORDER BY first_name ASC, last_name ASC
    ");

    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $employees
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Unable to retrieve employees."
    ]);
}