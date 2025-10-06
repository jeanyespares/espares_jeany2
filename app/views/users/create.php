<<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Sign Up</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-purple-100 via-violet-200 to-purple-300 min-h-screen flex items-center justify-center font-sans text-gray-700">

  <div class="bg-white/70 backdrop-blur-lg p-8 rounded-3xl shadow-xl w-full max-w-md animate-fadeIn border border-purple-200">
    
    <!-- Header -->
    <div class="flex flex-col items-center mb-6">
      <div class="bg-gradient-to-br from-purple-400 to-violet-500 rounded-full p-3 shadow-md">
        <i class="fa-solid fa-user-graduate text-white text-3xl drop-shadow-lg"></i>
      </div>
      <h2 class="text-2xl font-bold text-purple-800 mt-3">Create Your Student Account</h2>
      <p class="text-gray-500 text-sm">Join our student community today!</p>
    </div>

    <!-- Form -->
    <form action="<?=site_url('users/create')?>" method="POST" class="space-y-5">
      
      <!-- First Name -->
      <div>
        <label class="block text-purple-700 mb-1 font-medium">First Name</label>
        <input type="text" name="first_name" placeholder="Enter your first name" required
               class="w-full px-4 py-3 bg-purple-50 text-gray-700 border border-purple-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:outline-none shadow-sm transition duration-200">
      </div>

      <!-- Last Name -->
      <div>
        <label class="block text-purple-700 mb-1 font-medium">Last Name</label>
        <input type="text" name="last_name" placeholder="Enter your last name" required
               class="w-full px-4 py-3 bg-purple-50 text-gray-700 border border-purple-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:outline-none shadow-sm transition duration-200">
      </div>

      <!-- Email -->
      <div>
        <label class="block text-purple-700 mb-1 font-medium">Email Address</label>
        <input type="email" name="email" placeholder="Enter your email" required
               class="w-full px-4 py-3 bg-purple-50 text-gray-700 border border-purple-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:outline-none shadow-sm transition duration-200">
      </div>

      <!-- Sign Up Button -->
      <button type="submit"
              class="w-full bg-gradient-to-r from-purple-400 to-violet-500 hover:from-purple-500 hover:to-violet-600 text-white font-semibold py-3 rounded-xl shadow-lg transition duration-300 transform hover:scale-105">
        <i class="fa-solid fa-user-plus mr-2"></i> Sign Up
      </button>
    </form>
  </div>
</body>
</html>
