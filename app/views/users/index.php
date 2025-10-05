<!DOCTYPE html>
<html>
<head>
    <title>Student Directory</title>
    <style>
        .header { display: flex; justify-content: space-between; align-items: center; padding: 15px; border-bottom: 1px solid #eee; }
        .container { padding: 20px; }
        .controls-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .btn { padding: 5px 10px; text-decoration: none; color: white; border-radius: 3px; margin-left: 5px; font-size: 0.9em; }
        .btn-primary { background-color: #007bff; }
        .btn-secondary { background-color: #6c757d; }
        .btn-danger { background-color: #dc3545; }
        .btn-success { background-color: #28a745; }
        .btn-info { background-color: #17a2b8; }
        .text-muted { color: #6c757d; font-style: italic; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<div class="header">
    <h1>Registered BSIT Students 💖</h1>
    
    <div class="auth-buttons">
        <?php if ($is_logged_in): ?>
            <span style="margin-right: 15px;">Welcome, <?= html_escape($this->session->userdata('user')['username']) ?>!</span>
            <a href="<?= site_url('users/logout') ?>" class="btn btn-danger">Logout</a> 
        <?php else: ?>
            <a href="<?= site_url('users/login') ?>" class="btn btn-primary">Login</a>
            <a href="<?= site_url('users/register') ?>" class="btn btn-secondary">Register</a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert-error">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="controls-row">
        <form method="get" action="<?= site_url('users/index') ?>" class="search-form" style="display: flex;">
            <input type="text" name="q" placeholder="Search student..." value="<?= html_escape($q) ?>" style="padding: 5px; border: 1px solid #ccc; border-radius: 3px;">
            <button type="submit" class="btn btn-secondary" style="margin-left: 5px;">Search</button>
        </form>

        <?php if ($is_admin): ?>
            <a href="<?= site_url('users/create') ?>" class="btn btn-success">
                + Add Student
            </a>
        <?php endif; ?>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>FIRSTNAME</th>
                <th>LASTNAME</th>
                <th>EMAIL</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= html_escape($user['id']) ?></td>
                    <td><?= html_escape($user['fname']) ?></td>
                    <td><?= html_escape($user['lname']) ?></td>
                    <td><?= html_escape($user['email']) ?></td>
                    
                    <td>
                        <?php if ($is_admin): ?>
                            <a href="<?= site_url('users/update/' . $user['id']) ?>" class="btn btn-sm btn-info">Update</a>
                            <a href="<?= site_url('users/delete/' . $user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                        <?php else: ?>
                            <span class="text-muted">View Only</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No students found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination" style="margin-top: 20px; text-align: center;">
        <?= $pagination ?>
    </div>
</div>

</body>
</html>