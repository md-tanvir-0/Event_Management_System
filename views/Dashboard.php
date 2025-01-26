<?php
require_once('../controllers/dashboardController.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #007BFF;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .content {
            margin: 2rem;
        }
        .logout {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: #dc3545;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Your Dashboard</h1>
    </div>
    <div class="content">
        <p>Hello, <strong><?php echo htmlspecialchars($fullName); ?></strong>!</p>
        <p>You are successfully logged in.</p>
        <a class="logout" href="../controllers/logoutController.php">Logout</a>
    </div>
</body>
</html>