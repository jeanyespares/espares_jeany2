<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Sign Up</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-pink-100 via-fuchsia-200 to-purple-300 min-h-screen flex items-center justify-center font-poppins text-gray-700">

  <div class="bg-white/80 backdrop-blur-lg p-8 rounded-3xl shadow-2xl w-full max-w-md border-4 border-pink-200 animate-fadeIn">
    
    <!-- Header -->
    <div class="flex flex-col items-center mb-6">
      <div class="bg-gradient-to-br from-pink-400 via-fuchsia-500 to-purple-500 rounded-full p-3 shadow-lg">
        <i class="fa-solid fa-user-graduate text-white text-3xl drop-shadow-lg"></i>
      </div>
      <h2 class="text-2xl font-bold text-pink-600 mt-3 font-title">Create Your Student Account</h2>
      <p class="text-gray-500 text-sm">Join our student community today!</p>
    </div>

    <!-- Form -->
    <form action="<?=site_url('users/create')?>" method="POST" class="space-y-5">
      
      <!-- First Name -->
      <div>
        <label class="block text-pink-600 mb-1 font-semibold">First Name</label>
        <input type="text" name="first_name" placeholder="Enter your first name" required
               class="w-full px-4 py-3 bg-pink-50 text-gray-700 border-2 border-pink-300 rounded-2xl focus:ring-2 focus:ring-pink-400 focus:outline-none shadow-sm transition duration-200">
      </div>

      <!-- Last Name -->
      <div>
        <label class="block text-pink-600 mb-1 font-semibold">Last Name</label>
        <input type="text" name="last_name" placeholder="Enter your last name" required
               class="w-full px-4 py-3 bg-pink-50 text-gray-700 border-2 border-pink-300 rounded-2xl focus:ring-2 focus:ring-pink-400 focus:outline-none shadow-sm transition duration-200">
      </div>

      <!-- Email -->
      <div>
        <label class="block text-pink-600 mb-1 font-semibold">Email Address</label>
        <input type="email" name="email" placeholder="Enter your email" required
               class="w-full px-4 py-3 bg-pink-50 text-gray-700 border-2 border-pink-300 rounded-2xl focus:ring-2 focus:ring-pink-400 focus:outline-none shadow-sm transition duration-200">
      </div>

      <!-- Sign Up Button -->
      <button type="submit"
              class="w-full bg-gradient-to-r from-pink-400 via-fuchsia-500 to-purple-500 hover:from-pink-500 hover:via-fuchsia-600 hover:to-purple-600 text-white font-bold py-3 rounded-2xl shadow-lg transition duration-300 transform hover:scale-105 flex justify-center items-center gap-2">
        <i class="fa-solid fa-user-plus"></i> Sign Up
      </button>
    </form>
  </div>

</body>
</html>
