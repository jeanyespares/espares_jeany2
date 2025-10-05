<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f3;
            margin: 0;
            padding: 0;
        }
        .header {
            background: #343a40;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .content {
            margin: 50px auto;
            width: 85%;
            max-width: 800px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: white;
            background: #dc3545;
            padding: 10px 15px;
            border-radius: 5px;
        }
        a:hover {
            background: #b52a37;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Admin Dashboard</h2>
        <p>Welcome, <?= $_SESSION['username'] ?> (Role: <?= $_SESSION['role'] ?>)</p>
    </div>

    <div class="content">
        <h3>User Management</h3>
        <p>As an admin, you can view, update, or delete user records below.</p>

        <table>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php foreach($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['fname'] . ' ' . $user['lname'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td>
                        <a href="<?= site_url('users/update/'.$user['id']) ?>" style="background:#007bff;">Edit</a>
                        <a href="<?= site_url('users/delete/'.$user['id']) ?>" style="background:#dc3545;">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <a href="<?= site_url('users/logout') ?>">Logout</a>
    </div>
</body>
</html>
