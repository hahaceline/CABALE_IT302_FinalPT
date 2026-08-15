<?php

require_once "db_config.php";

/*
|--------------------------------------------------------------------------
| Get Departments from Microservice
|--------------------------------------------------------------------------
*/

$departments = [];
$apiError = "";

$apiUrl = "http://nginx:81/api/departments.php";

$ch = curl_init($apiUrl);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

$apiResponse = curl_exec($ch);

if ($apiResponse === false) {

    $apiError = "Unable to connect to the Department Microservice.";

} else {

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode !== 200) {

        $apiError = "Department API returned HTTP status: " . $httpCode;

    } else {

        $apiData = json_decode($apiResponse, true);

        if (
            isset($apiData["success"]) &&
            $apiData["success"] === true &&
            isset($apiData["data"])
        ) {

            $departments = $apiData["data"];

        } else {

            $apiError = "Invalid response from Department Microservice.";
        }
    }
}

curl_close($ch);


/*
|--------------------------------------------------------------------------
| Add Employee
|--------------------------------------------------------------------------
*/

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $employeeId = trim($_POST["employee_id"] ?? "");
    $firstName = trim($_POST["first_name"] ?? "");
    $lastName = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $departmentId = $_POST["department_id"] ?? "";
    $position = trim($_POST["position"] ?? "");

    if (
        $employeeId === "" ||
        $firstName === "" ||
        $lastName === "" ||
        $email === "" ||
        $departmentId === "" ||
        $position === ""
    ) {

        $message = "Please complete all fields.";

    } else {

        try {

            $stmt = $pdo->prepare("
                INSERT INTO employees
                (
                    employee_id,
                    first_name,
                    last_name,
                    email,
                    department_id,
                    position
                )
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $employeeId,
                $firstName,
                $lastName,
                $email,
                $departmentId,
                $position
            ]);

            header("Location: index.php");
            exit;

        } catch (PDOException $e) {

            $message = "Unable to add employee. The Employee ID may already exist.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Employee</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 40px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
        }

        h1 {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            margin-top: 25px;
            padding: 10px 20px;
            background: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #555;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
        }

        .message {
            background: #f8d7da;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .api-error {
            background: #fff3cd;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Add Employee</h1>

    <?php if ($apiError !== ""): ?>

        <div class="api-error">
            <?= htmlspecialchars($apiError) ?>
        </div>

    <?php endif; ?>

    <?php if ($message !== ""): ?>

        <div class="message">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php endif; ?>


    <form method="POST">

        <label for="employee_id">
            Employee ID
        </label>

        <input
            type="text"
            id="employee_id"
            name="employee_id"
            required
        >


        <label for="first_name">
            First Name
        </label>

        <input
            type="text"
            id="first_name"
            name="first_name"
            required
        >


        <label for="last_name">
            Last Name
        </label>

        <input
            type="text"
            id="last_name"
            name="last_name"
            required
        >


        <label for="email">
            Email
        </label>

        <input
            type="email"
            id="email"
            name="email"
            required
        >


        <label for="department_id">
            Department
        </label>

        <select
            id="department_id"
            name="department_id"
            required
        >

            <option value="">
                Select Department
            </option>

            <?php foreach ($departments as $department): ?>

                <option value="<?= htmlspecialchars($department["id"]) ?>">
                    <?= htmlspecialchars($department["department_name"]) ?>
                </option>

            <?php endforeach; ?>

        </select>


        <label for="position">
            Position
        </label>

        <input
            type="text"
            id="position"
            name="position"
            required
        >


        <button type="submit">
            Add Employee
        </button>

    </form>


    <a href="index.php" class="back">
        ← Back to Employees
    </a>

</div>

</body>

</html>