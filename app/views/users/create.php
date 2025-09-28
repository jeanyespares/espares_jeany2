<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enroll Student</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600&family=Pacifico&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Baloo 2', cursive;
      background: linear-gradient(135deg, #ffe4e6, #fce7f3, #ede9fe, #dbeafe);
    }
    .font-title {
      font-family: 'Pacifico', cursive;
    }
    .btn-hover:hover {
      transform: scale(1.07) rotate(-1deg);
      box-shadow: 0 0 15px #ff99cc, 0 0 25px #ffccff;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center">

  <div class="bg-white p-10 rounded-3xl shadow-2xl w-full max-w-md border-4 border-pink-200">

    <!-- Header -->
    <div class="flex flex-col items-center mb-6">
      <div class="bg-gradient-to-br from-pink-400 to-fuchsia-500 rounded-full p-5 shadow-md animate-pulse">
        <i class="fa-solid fa-hat-wizard text-white text-4xl"></i>
      </div>
      <h2 class="font-title text-3xl text-pink-600 mt-3 drop-shadow-lg">Add New Student 💖</h2>
      <p class="text-sm text-fuchsia-600 mt-1">Let's welcome a magical new student ✨</p>
    </div>

    <!-- Form -->
    <form action="<?=site_url('users/create')?>" method="POST" class="space-y-5">
      <div>
        <label class="block text-pink-700 mb-1 font-bold">First Name</label>
        <input type="text" name="fname" required placeholder="enter first name"
               class="w-full px-4 py-3 border-2 border-pink-300 rounded-xl focus:ring-4 focus:ring-pink-400 shadow-sm placeholder:text-pink-400 placeholder:italic">
      </div>

      <div>
        <label class="block text-pink-700 mb-1 font-bold">Last Name</label>
        <input type="text" name="lname" required placeholder="enter last name"
               class="w-full px-4 py-3 border-2 border-pink-300 rounded-xl focus:ring-4 focus:ring-pink-400 shadow-sm placeholder:text-pink-400 placeholder:italic">
      </div>

      <div>
        <label class="block text-pink-700 mb-1 font-bold">Email</label>
        <input type="email" name="email" required placeholder="enter your email"
               class="w-full px-4 py-3 border-2 border-pink-300 rounded-xl focus:ring-4 focus:ring-pink-400 shadow-sm placeholder:text-pink-400 placeholder:italic">
      </div>

      <button type="submit"
              class="btn-hover w-full bg-gradient-to-r from-pink-400 to-fuchsia-500 text-white font-bold py-3 rounded-xl shadow-xl transition transform hover:scale-105">
         <i class="fa-solid fa-user-plus"></i> Add
      </button>
    </form>
  </div>
</body>
</html>
