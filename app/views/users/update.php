<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-600 to-cyan-400 min-h-screen flex items-center justify-center font-sans">

  <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md animate-fadeIn">
    <h2 class="text-2xl font-semibold text-center text-blue-600 mb-6">Update Now....</h2>

    <form action="<?=site_url('users/update/'.$user['id'])?>" method="POST" class="space-y-4">
      <!-- First Name -->
      <div>
        <label class="block text-gray-700 mb-1">First Name</label>
        <input type="text" name="fname" value="<?= html_escape($user['fname'])?>" required
               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>

      <!-- Last Name -->
      <div>
        <label class="block text-gray-700 mb-1">Last Name</label>
        <input type="text" name="lname" value="<?= html_escape($user['lname'])?>" required
               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>

      <!-- Email -->
      <div>
        <label class="block text-gray-700 mb-1">Email Address</label>
        <input type="email" name="email" value="<?= html_escape($user['email'])?>" required
               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>

      <!-- Button -->
      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-xl shadow-md transition duration-200">
        Update
      </button>
    </form>
  </div>

  <!-- Small fade-in animation -->
  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
      animation: fadeIn 0.8s ease;
    }
  </style>
</body>
</html>
