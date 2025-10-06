<<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-100 via-purple-200 to-purple-300 min-h-screen flex items-center justify-center font-sans text-purple-900">

  <div class="bg-white/70 backdrop-blur-xl p-8 rounded-2xl shadow-2xl w-full max-w-md animate-fadeIn border border-purple-200">
    <h2 class="text-2xl font-semibold text-center text-purple-900 mb-6">Update Now...</h2>

    <form action="<?=site_url('users/update/'.$user['id'])?>" method="POST" class="space-y-4">
      <div>
        <label class="block text-purple-800 mb-1">First Name</label>
        <input type="text" name="first_name" value="<?= html_escape($user['first_name'])?>" required
               class="w-full px-4 py-3 bg-purple-50 text-purple-900 border border-purple-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:outline-none">
      </div>

      <div>
        <label class="block text-purple-800 mb-1">Last Name</label>
        <input type="text" name="last_name" value="<?= html_escape($user['last_name'])?>" required
               class="w-full px-4 py-3 bg-purple-50 text-purple-900 border border-purple-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:outline-none">
      </div>

      <div>
        <label class="block text-purple-800 mb-1">Email Address</label>
        <input type="email" name="email" value="<?= html_escape($user['email'])?>" required
               class="w-full px-4 py-3 bg-purple-50 text-purple-900 border border-purple-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:outline-none">
      </div>

      <button type="submit"
              class="w-full bg-gradient-to-r from-purple-400 to-purple-600 hover:from-purple-500 hover:to-purple-700 text-white font-medium py-3 rounded-xl shadow-md transition duration-200">
        Update
      </button>
    </form>
  </div>
</body>
</html>
