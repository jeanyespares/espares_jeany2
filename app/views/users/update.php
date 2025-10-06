<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #ffe4e6, #fce7f3, #ede9fe, #dbeafe); }
    .font-title { font-family: 'Dancing Script', cursive; }
    .btn-hover:hover { transform: scale(1.07) rotate(-1deg); box-shadow: 0 0 15px #ff99cc, 0 0 25px #ffccff; }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center">

  <div class="bg-white shadow-2xl rounded-3xl p-8 w-full max-w-md border-4 border-pink-200 animate-fadeIn">
    <h2 class="text-2xl font-title text-center text-pink-600 mb-6">Update Student Info</h2>

    <form action="<?=site_url('users/update/'.$user['id'])?>" method="POST" class="space-y-5">
      <div>
        <label class="block text-pink-600 mb-1 font-semibold">First Name</label>
        <input type="text" name="first_name" value="<?= html_escape($user['fname'])?>" required
               class="w-full px-4 py-3 bg-pink-50 text-gray-700 border-2 border-pink-300 rounded-2xl focus:ring-2 focus:ring-pink-400 focus:outline-none shadow-sm transition duration-200">
      </div>

      <div>
        <label class="block text-pink-600 mb-1 font-semibold">Last Name</label>
        <input type="text" name="last_name" value="<?= html_escape($user['lname'])?>" required
               class="w-full px-4 py-3 bg-pink-50 text-gray-700 border-2 border-pink-300 rounded-2xl focus:ring-2 focus:ring-pink-400 focus:outline-none shadow-sm transition duration-200">
      </div>

      <div>
        <label class="block text-pink-600 mb-1 font-semibold">Email Address</label>
        <input type="email" name="email" value="<?= html_escape($user['email'])?>" required
               class="w-full px-4 py-3 bg-pink-50 text-gray-700 border-2 border-pink-300 rounded-2xl focus:ring-2 focus:ring-pink-400 focus:outline-none shadow-sm transition duration-200">
      </div>

      <button type="submit"
              class="w-full bg-gradient-to-r from-pink-400 via-fuchsia-500 to-purple-500 hover:from-pink-500 hover:via-fuchsia-600 hover:to-purple-600 text-white font-bold py-3 rounded-2xl shadow-lg transition duration-300 transform hover:scale-105 flex justify-center items-center gap-2">
        <i class="fa-solid fa-pen-to-square"></i> Update
      </button>
    </form>
  </div>

</body>
</html>
