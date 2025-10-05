<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #007bff;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h2 {
            margin: 0;
        }
        .content {
            padding: 40px;
        }
        a {
            color: white;
            text-decoration: none;
            background: #dc3545;
            padding: 6px 12px;
            border-radius: 4px;
        }
        a:hover {
            background: #c82333;
        }
        .info {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            width: 400px;
            margin: 0 auto;
            text-align: center;
        }
        .role {
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h2>Dashboard</h2>
    <a href="<?= site_url('users/logout'); ?>">Logout</a>
</div>

<div class="content">
    <div class="info">
        <h3>Welcome, <?= htmlspecialchars($username); ?>!</h3>
        <p>Your role is: <span class="role"><?= htmlspecialchars($role); ?></span></p>
        <p>Access other features using the navigation.</p>
    </div>
</div>

</body>
</html>
