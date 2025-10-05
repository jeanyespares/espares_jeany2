<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-pink-200 via-purple-200 to-blue-200">
  <div class="bg-white p-10 rounded-3xl shadow-2xl w-96">
    <h2 class="text-3xl font-bold mb-6 text-center">Login</h2>

    <?php if (!empty($error)) echo "<p class='text-red-500 mb-4'>$error</p>"; ?>

    <form method="post" action="">
      <div class="mb-4">
        <label class="block mb-1 font-semibold">Username</label>
        <input type="text" name="username" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400" required>
      </div>

      <div class="mb-6">
        <label class="block mb-1 font-semibold">Password</label>
        <input type="password" name="password" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400" required>
      </div>

      <button type="submit" class="w-full bg-gradient-to-r from-pink-400 to-purple-500 text-white py-2 rounded-xl shadow hover:scale-105 transition-all">Login</button>
    </form>
  </div>
</body>
</html>
