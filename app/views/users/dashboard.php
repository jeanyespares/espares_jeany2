<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .header {
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .content {
            margin: 50px auto;
            width: 80%;
            max-width: 600px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: white;
            background: #007bff;
            padding: 10px 15px;
            border-radius: 5px;
        }
        a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Welcome, <?= $_SESSION['username'] ?>!</h2>
    </div>

    <div class="content">
        <p>You are logged in as <strong><?= $_SESSION['role'] ?></strong>.</p>
        <p>This is your user dashboard.</p>

        <?php if($_SESSION['role'] === 'admin'): ?>
            <a href="<?= site_url('admin/dashboard') ?>">Go to Admin Dashboard</a><br>
        <?php endif; ?>

        <a href="<?= site_url('users/logout') ?>">Logout</a>
    </div>
</body>
</html>
