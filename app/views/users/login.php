<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Student Directory</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #ffe4e6, #fce7f3, #ede9fe, #dbeafe); }
    .font-title { font-family: 'Dancing Script', cursive; }
    .btn-hover:hover { transform: scale(1.05) rotate(-1deg); box-shadow: 0 0 15px #ff99cc, 0 0 25px #ffccff; }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center">

  <div class="bg-white p-10 rounded-3xl shadow-2xl w-96 border-4 border-pink-200">
    <h2 class="text-3xl font-title font-bold mb-6 text-center text-pink-500">Student Directory Login</h2>

    <?php if (!empty($error)) : ?>
      <p class="text-red-500 mb-4 text-center font-semibold"><?= $error ?></p>
    <?php endif; ?>

    <form method="post" action="">
      <div class="mb-4">
        <label class="block mb-1 font-semibold text-gray-700">Username</label>
        <input type="text" name="username" placeholder="Enter username" class="w-full px-4 py-2 border-2 border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400" required>
      </div>

      <div class="mb-6">
        <label class="block mb-1 font-semibold text-gray-700">Password</label>
        <input type="password" name="password" placeholder="Enter password" class="w-full px-4 py-2 border-2 border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400" required>
      </div>

      <button type="submit" class="w-full bg-gradient-to-r from-pink-400 to-purple-500 text-white py-2 rounded-xl shadow btn-hover font-bold transition-all duration-300">
        Login
      </button>
    </form>

    <p class="mt-4 text-center text-gray-500 text-sm">
      Use <strong>Username:</strong> jeany | <strong>Password:</strong> jeany21
    </p>
  </div>

</body>
</html>
