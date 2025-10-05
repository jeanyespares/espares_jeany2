<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Create User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4">Create User</h1>

    <?php if (!empty($error ?? null)): ?>
        <div class="bg-red-100 text-red-700 p-2 rounded mb-3"><?= html_escape($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= site_url('admin/users/create') ?>" class="space-y-3">
        <div>
            <label class="block text-sm font-medium">Username</label>
            <input type="text" name="username" required class="w-full border p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-medium">Password</label>
            <input type="password" name="password" required class="w-full border p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-medium">Role</label>
            <select name="role" class="w-full border p-2 rounded">
                <option value="user">user</option>
                <option value="admin">admin</option>
            </select>
        </div>
        <div>
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Create</button>
        </div>
    </form>
</div>
</body>
</html>