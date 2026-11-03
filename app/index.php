<?php
require_once 'db.php';

$pdo->exec("
    CREATE TABLE IF NOT EXISTS employees (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL
    )
");

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare(
            "INSERT INTO employees (name, email) VALUES (?, ?)"
        );
        $stmt->execute([$name, $email]);
        $message = "Employee added successfully!";
    } else {
        $message = "Please enter valid employee details.";
    }
}

$employees = $pdo->query(
    "SELECT * FROM employees ORDER BY id DESC"
)->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>ABC Technologies</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial;
            background: #f1f5f9;
            padding: 30px;
        }
        .container {
            max-width: 750px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
        }
        input, button {
            padding: 12px;
            margin: 7px 0;
            width: 100%;
            box-sizing: border-box;
        }
        button {
            background: #2563eb;
            color: white;
            border: 0;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>ABC Technologies</h1>
    <h3>Employee Management System</h3>

    <p>Database connection: <strong>Connected</strong></p>

    <h3>Add Employee</h3>

    <p><?= htmlspecialchars($message) ?></p>

    <form method="POST">
        <input name="name" placeholder="Employee Name" required>
        <input name="email" type="email"
               placeholder="Employee Email" required>
        <button type="submit">Add Employee</button>
    </form>

    <h3>Employee Records</h3>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
        </tr>

        <?php foreach ($employees as $employee): ?>
        <tr>
            <td><?= htmlspecialchars((string)$employee['id']) ?></td>
            <td><?= htmlspecialchars($employee['name']) ?></td>
            <td><?= htmlspecialchars($employee['email']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
