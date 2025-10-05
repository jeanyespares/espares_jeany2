<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enroll Student</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Inter', sans-serif; /* clean, professional font */
      background: linear-gradient(135deg, #1a202c, #2d3748, #4a5568);
      color: #e2e8f0;
    }
    .btn-hover:hover {
      transform: scale(1.05);
      box-shadow: 0 0 12px rgba(66, 153, 225, 0.6);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center">

  <div class="bg-gray-900 p-10 rounded-2xl shadow-2xl w-full max-w-md border border-gray-700">

    <!-- Header -->
    <div class="flex flex-col items-center mb-6">
      <div class="bg-blue-600 rounded-full p-5 shadow-md">
        <i class="fa-solid fa-user-graduate text-white text-4xl"></i>
      </div>
      <h2 class="text-2xl font-bold text-white mt-3 tracking-wide">Add New Student Record</h2>
      <p class="text-sm text-gray-400 mt-1">New student record</p>
    </div>

    <!-- Form -->
    <form action="<?=site_url('users/create')?>" method="POST" class="space-y-5">
      <div>
        <label class="block text-gray-300 mb-1 font-medium">First Name</label>
        <input type="text" name="fname" required placeholder="Enter first name"
               class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-white rounded-lg focus:ring-2 focus:ring-blue-500 placeholder-gray-500">
      </div>

      <div>
        <label class="block text-gray-300 mb-1 font-medium">Last Name</label>
        <input type="text" name="lname" required placeholder="Enter last name"
               class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-white rounded-lg focus:ring-2 focus:ring-blue-500 placeholder-gray-500">
      </div>

      <div>
        <label class="block text-gray-300 mb-1 font-medium">Email</label>
        <input type="email" name="email" required placeholder="Enter email address"
               class="w-full px-4 py-3 border border-gray-600 bg-gray-800 text-white rounded-lg focus:ring-2 focus:ring-blue-500 placeholder-gray-500">
      </div>

      <button type="submit"
              class="btn-hover w-full bg-blue-600 text-white font-semibold py-3 rounded-lg shadow-lg transition transform hover:scale-105">
         <i class="fa-solid fa-user-plus"></i> Add Student Record
      </button>
    </form>
  </div>
</body>
</html>
