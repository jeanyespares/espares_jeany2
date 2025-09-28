<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Student</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=IM+Fell+English&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'IM Fell English', serif;
      background-color: #fae5b3;
    }
    .font-title {
      font-family: 'Cinzel Decorative', cursive;
      letter-spacing: 2px;
    }
    .btn-hover:hover {
      box-shadow: 0 0 12px gold, 0 0 24px crimson;
      transform: scale(1.05);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center">

  <div class="bg-yellow-50 p-8 rounded-3xl shadow-2xl w-full max-w-md border-4 border-yellow-700">

    <!-- Header -->
    <div class="flex flex-col items-center mb-6">
      <div class="bg-gradient-to-br from-green-700 to-yellow-600 rounded-full p-4 shadow-md">
        <i class="fa-solid fa-wand-magic-sparkles text-yellow-100 text-3xl"></i>
      </div>
      <h2 class="font-title text-2xl text-red-900 mt-3">Update Information</h2>
    </div>

    <!-- Form -->
    <form action="<?=site_url('users/update/'.$user['id'])?>" method="POST" class="space-y-5">
      <div>
        <label class="block text-red-900 mb-1 font-bold">First Name</label>
        <input type="text" name="fname" value="<?= html_escape($user['fname'])?>" required
               class="w-full px-4 py-3 border-2 border-yellow-700 rounded-xl focus:ring-2 focus:ring-green-600 shadow-sm">
      </div>

      <div>
        <label class="block text-red-900 mb-1 font-bold">Last Name</label>
        <input type="text" name="lname" value="<?= html_escape($user['lname'])?>" required
               class="w-full px-4 py-3 border-2 border-yellow-700 rounded-xl focus:ring-2 focus:ring-green-600 shadow-sm">
      </div>

      <div>
        <label class="block text-red-900 mb-1 font-bold">Email</label>
        <input type="email" name="email" value="<?= html_escape($user['email'])?>" required
               class="w-full px-4 py-3 border-2 border-yellow-700 rounded-xl focus:ring-2 focus:ring-green-600 shadow-sm">
      </div>

      <button type="submit"
              class="btn-hover w-full bg-gradient-to-r from-green-700 to-green-900 text-yellow-100 font-bold py-3 rounded-xl shadow-lg transition">
         Update
      </button>
    </form>
  </div>
</body>
</html>
