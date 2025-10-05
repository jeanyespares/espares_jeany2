<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Student</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #1e293b, #0f172a);
      color: #1e293b;
    }
    .btn-hover:hover {
      transform: scale(1.05);
      box-shadow: 0 0 18px rgba(59,130,246,0.5);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center">

  <div class="bg-white/95 backdrop-blur p-10 rounded-2xl shadow-2xl w-full max-w-md border border-slate-300">

    <!-- Header -->
    <div class="flex flex-col items-center mb-6">
      <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-full p-5 shadow-md">
        <i class="fa-solid fa-user-pen text-white text-4xl"></i>
      </div>
      <h2 class="text-2xl font-bold text-slate-800 mt-3">Update Student Info</h2>
      <p class="text-sm text-slate-500 mt-1">Keep the student profile up to date</p>
    </div>

    <!-- Form -->
    <form action="<?=site_url('users/update/'.$user['id'])?>" method="POST" class="space-y-5">
      <div>
        <label class="block text-slate-700 mb-1 font-semibold">First Name</label>
        <input type="text" name="fname" value="<?= html_escape($user['fname'])?>" required
               class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-4 focus:ring-blue-400 focus:border-blue-500 shadow-sm">
      </div>

      <div>
        <label class="block text-slate-700 mb-1 font-semibold">Last Name</label>
        <input type="text" name="lname" value="<?= html_escape($user['lname'])?>" required
               class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-4 focus:ring-blue-400 focus:border-blue-500 shadow-sm">
      </div>

      <div>
        <label class="block text-slate-700 mb-1 font-semibold">Email</label>
        <input type="email" name="email" value="<?= html_escape($user['email'])?>" required
               class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-4 focus:ring-blue-400 focus:border-blue-500 shadow-sm">
      </div>

      <button type="submit"
              class="btn-hover w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold py-3 rounded-lg shadow-lg transition transform hover:scale-105">
         <i class="fa-solid fa-save"></i> Save Changes
      </button>
    </form>
  </div>
</body>
</html>
