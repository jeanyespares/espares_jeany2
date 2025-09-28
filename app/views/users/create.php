<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Student</title>
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

  <div class="bg-yellow-50 p-8 rounded-3xl shadow-2xl w-full max-w-md border-4 border-yellow-700 text-center">

    <!-- Header -->
    <div class="flex flex-col items-center mb-6">
      <div class="bg-gradient-to-br from-red-800 to-red-600 rounded-full p-4 shadow-md">
        <i class="fa-solid fa-triangle-exclamation text-yellow-100 text-3xl"></i>
      </div>
      <h2 class="font-title text-2xl text-red-900 mt-3">Confirm Deletion</h2>
    </div>

    <!-- Warning Text -->
    <p class="text-red-900 font-bold mb-6">
      Are you sure you want to delete<br>
      <span class="text-red-700">"<?= html_escape($user['fname'] . ' ' . $user['lname']) ?>"</span>?
    </p>

    <!-- Buttons -->
    <div class="flex justify-center gap-4">
      <a href="<?=site_url('users/delete/'.$user['id'])?>"
         class="btn-hover bg-red-700 hover:bg-red-900 text-yellow-100 px-6 py-2 rounded-lg shadow font-bold transition">
        <i class="fa-solid fa-trash"></i> Yes, Delete
      </a>
      <a href="<?=site_url('users')?>"
         class="btn-hover bg-green-700 hover:bg-green-900 text-yellow-100 px-6 py-2 rounded-lg shadow font-bold transition">
        <i class="fa-solid fa-ban"></i> Cancel
      </a>
    </div>
  </div>

</body>
</html>
