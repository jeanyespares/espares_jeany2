```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Directory</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Dancing+Script:wght=700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { 
      font-family: 'Poppins', sans-serif; 
      background: linear-gradient(135deg, #f0f9ff, #e0f2fe, #dbeafe);
    }
    .font-title { 
      font-family: 'Dancing Script', cursive; 
    }
    .btn-hover:hover { 
      transform: translateY(-2px); 
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
    }
    table { 
      border-collapse: separate; 
      border-spacing: 0 10px; 
    }
  </style>
</head>
<body class="p-4 md:p-10">

  <div class="max-w-6xl mx-auto">
    
    <!-- HEADER AND NAVIGATION -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 p-4 bg-white rounded-xl shadow-lg border-b-4 border-blue-400">
      <h1 class="text-4xl font-title text-blue-600 mb-4 sm:mb-0">
        <i class="fa-solid fa-users"></i> Student Directory
      </h1>
      
      <!-- AUTH LINKS: Logout if logged in, otherwise Login/Register -->
      <div class="flex space-x-2">
        <?php if ($is_logged_in): ?>
          <a href="<?= site_url('users/logout') ?>" class="btn-hover bg-red-500 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
            <i class="fa-solid fa-sign-out-alt"></i> Logout
          </a>
        <?php else: ?>
          <a href="<?= site_url('users/login') ?>" class="btn-hover bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
            <i class="fa-solid fa-sign-in-alt"></i> Admin Login
          </a>
          <a href="<?= site_url('users/register') ?>" class="btn-hover bg-purple-500 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
            <i class="fa-solid fa-user-plus"></i> Register Admin
          </a>
        <?php endif; ?>
      </div>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if (isset($_SESSION['flashdata']['success'])): ?>
      <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg" role="alert">
        <p class="font-bold">Success!</p>
        <p><?= $_SESSION['flashdata']['success'] ?></p>
      </div>
      <?php unset($_SESSION['flashdata']['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flashdata']['error'])): ?>
      <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg" role="alert">
        <p class="font-bold">Error!</p>
        <p><?= $_SESSION['flashdata']['error'] ?></p>
      </div>
      <?php unset($_SESSION['flashdata']['error']); ?>
    <?php endif; ?>

    <!-- SEARCH AND ADD BUTTON -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
      
      <!-- Search Form -->
      <form method="GET" action="<?= site_url('/') ?>" class="w-full md:w-1/2">
        <div class="relative flex items-center">
          <input type="text" name="q" placeholder="Search by name or email..." value="<?= html_escape($q) ?>"
             class="w-full pl-10 pr-4 py-2 border-2 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition duration-300">
          <i class="fa-solid fa-search absolute left-3 text-gray-400"></i>
        </div>
      </form>
      
      <!-- ADD NEW STUDENT BUTTON (ADMIN ONLY) -->
      <?php if ($is_admin): ?>
        <a href="<?= site_url('users/create') ?>"
            class="btn-hover w-full md:w-auto bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold py-2 px-6 rounded-xl shadow-md transition duration-300 flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus-circle"></i> Add New Student
        </a>
      <?php endif; ?>
    </div>
    
    <!-- STUDENT TABLE -->
    <div class="overflow-x-auto shadow-2xl rounded-2xl border-4 border-blue-100 bg-white">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-blue-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider rounded-tl-xl">ID</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Full Name</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
            
            <!-- ACTIONS COLUMN HEADER (ADMIN ONLY) -->
            <?php if ($is_admin): ?>
              <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider rounded-tr-xl">Actions</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
          <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
              <tr class="hover:bg-blue-50 transition duration-150 ease-in-out">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= html_escape($user['id']) ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= html_escape($user['fname'] . ' ' . $user['lname']) ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 font-medium"><?= html_escape($user['email']) ?></td>
                
                <!-- ACTIONS CELL (ADMIN ONLY) -->
                <?php if ($is_admin): ?>
                  <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                    <a href="<?= site_url('users/update/' . $user['id']) ?>" class="text-indigo-600 hover:text-indigo-900 mx-2 transition duration-150">
                      <i class="fa-solid fa-edit"></i> Edit
                    </a>
                    <a href="<?= site_url('users/delete/' . $user['id']) ?>" class="text-red-600 hover:text-red-900 mx-2 transition duration-150"
                      onclick="return confirm('Are you sure you want to delete this student?')">
                      <i class="fa-solid fa-trash-alt"></i> Delete
                    </a>
                  </td>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <!-- Adjusted colspan based on whether Actions column is present -->
              <td colspan="<?= $is_admin ? 4 : 3 ?>" class="px-6 py-8 text-center text-gray-500">
                No student records found.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-6 flex justify-center">
      <?= $pagination ?>
    </div>

  </div>
</body>
</html>
