<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enroll Student</title>
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

  <div class="bg-yellow-100 p-10 rounded-3xl shadow-2xl w-full max-w-md border-4 border-yellow-700 relative">

    <!-- Magical sparkles -->
    <div class="absolute -top-4 -right-4 text-yellow-400 animate-bounce">
      <i class="fa-solid fa-star text-2xl"></i>
    </div>
    <div class="absolute -bottom-4 -left-4 text-yellow-400 animate-pulse">
      <i class="fa-solid fa-wand-sparkles text-2xl"></i>
    </div>

    <!-- Header -->
    <div class="flex flex-col items-center mb-6">
      <div class="bg-gradient-to-br from-red-700 to-yellow-600 rounded-full p-5 shadow-md animate-pulse">
        <i class="fa-solid fa-hat-wizard text-yellow-100 text-4xl"></i>
      </div>
      <h2 class="font-title text-3xl text-red-900 mt-3 drop-shadow-lg">Add New Student</h2>
      <p class="text-sm text-red-700 mt-1">Cast your spell to enroll a student ✨</p>
    </div>

    <!-- Form -->
    <form action="<?=site_url('users/create')?>" method="POST" class="space-y-5">
      <div>
        <label class="block text-red-900 mb-1 font-bold">First Name</label>
        <input type="text" name="fname" required placeholder="Enter first name"
               class="w-full px-4 py-3 border-2 border-yellow-700 rounded-xl focus:ring-4 focus:ring-red-500 shadow-sm placeholder:text-yellow-700 placeholder:italic">
      </div>

      <div>
        <label class="block text-red-900 mb-1 font-bold">Last Name</label>
        <input type="text" name="lname" required placeholder="Enter last name"
               class="w-full px-4 py-3 border-2 border-yellow-700 rounded-xl focus:ring-4 focus:ring-red-500 shadow-sm placeholder:text-yellow-700 placeholder:italic">
      </div>

      <div>
        <label class="block text-red-900 mb-1 font-bold">Email</label>
        <input type="email" name="email" required placeholder="Enter your email"
               class="w-full px-4 py-3 border-2 border-yellow-700 rounded-xl focus:ring-4 focus:ring-red-500 shadow-sm placeholder:text-yellow-700 placeholder:italic">
      </div>

      <button type="submit"
              class="btn-hover w-full bg-gradient-to-r from-red-700 to-yellow-600 text-yellow-100 font-bold py-3 rounded-xl shadow-xl transition transform hover:scale-105">
         <i class="fa-solid fa-feather-pointed"></i> Add Student
      </button>
    </form>
  </div>
</body>
</html>
