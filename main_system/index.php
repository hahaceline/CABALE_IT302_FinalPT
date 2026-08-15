<?php

require_once "db_config.php";

$stmt = $pdo->query("
    SELECT 
        e.id,
        e.employee_id,
        e.first_name,
        e.last_name,
        e.email,
        e.position,
        d.department_name
    FROM employees e
    LEFT JOIN departments d ON e.department_id = d.id
    ORDER BY e.id DESC
");

$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Employee Management System</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
        }

        h1 {
            margin-bottom: 5px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 25px;
        }

        .add-button {
            display: inline-block;
            padding: 10px 15px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .add-button:hover {
            background: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f0f0f0;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }

        .edit-button {
            color: #0066cc;
            text-decoration: none;
            margin-right: 8px;
        }

        .delete-button {
            color: #cc0000;
            text-decoration: none;
        }

        .edit-button:hover,
        .delete-button:hover {
            text-decoration: underline;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Employee Management System</h1>

    <p class="subtitle">
        Manage employee records.
    </p>

    <a href="create.php" class="add-button">
        Add Employee
    </a>

    <?php if (count($employees) > 0): ?>

        <table>

            <thead>

                <tr>

                    <th>Employee ID</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th>Department</th>

                    <th>Position</th>

                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach ($employees as $employee): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($employee['employee_id']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $employee['first_name'] . ' ' . $employee['last_name']
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($employee['email']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $employee['department_name'] ?? 'N/A'
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($employee['position']) ?>
                        </td>

                        <td>

                            <a
                                href="edit.php?id=<?= htmlspecialchars($employee['id']) ?>"
                                class="edit-button"
                            >
                                Edit
                            </a>

                            <a
                                href="delete.php?id=<?= htmlspecialchars($employee['id']) ?>"
                                class="delete-button"
                                onclick="return confirm('Are you sure you want to delete this employee?');"
                            >
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php else: ?>

        <div class="empty">
            No employee records found.
        </div>

    <?php endif; ?>

</div>

</body>

</html>