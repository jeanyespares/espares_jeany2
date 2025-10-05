<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Users</h1>
        <a href="<?= site_url('admin/users/create') ?>" class="bg-blue-600 text-white px-3 py-1 rounded">New User</a>
    </div>

    <table class="w-full table-auto border-collapse">
        <thead>
            <tr>
                <th class="border p-2">ID</th>
                <th class="border p-2">Username</th>
                <th class="border p-2">Role</th>
                <th class="border p-2">Created</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach (($users ?? []) as $u): ?>
            <tr>
                <td class="border p-2"><?= html_escape($u['id']) ?></td>
                <td class="border p-2"><?= html_escape($u['username']) ?></td>
                <td class="border p-2"><?= html_escape($u['role']) ?></td>
                <td class="border p-2"><?= html_escape($u['created_at'] ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>