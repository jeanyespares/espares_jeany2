<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Directory</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&family=IM+Fell+English&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?=base_url();?>/public/style.css">
<style>
    body { font-family: 'IM Fell English', serif; background: linear-gradient(135deg, #f3e8ff, #e0c7ff); }
    .font-title { font-family: 'Cinzel Decorative', cursive; letter-spacing: 2px; }
    .btn-hover:hover { box-shadow: 0 0 12px #c084fc, 0 0 24px #6d28d9; transform: scale(1.05); }
    .hp-page { padding: 6px 12px; border: 1px solid #a855f7; border-radius: 6px; color: #6b21a8; transition: 0.3s; }
    .hp-page:hover { background-color: #e9d5ff; }
    .hp-current { padding: 6px 12px; border-radius: 6px; background: #a855f7; color: #fff; font-weight: bold; }
</style>
</head>
<body class="min-h-screen text-[#3a2c56]">

<!-- Header -->
<nav class="bg-gradient-to-r from-purple-900 via-purple-700 to-purple-800 shadow-lg border-b-4 border-purple-400">
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
    <h1 class="text-purple-100 font-title text-2xl flex items-center gap-2">
      <i class="fa-solid fa-hat-wizard"></i> Student Directory
    </h1>
  </div>
</nav>

<!-- Content -->
<div class="max-w-6xl mx-auto mt-10 px-4">
  <div class="bg-purple-50 shadow-2xl rounded-3xl p-6 border-4 border-purple-600">

    <!-- Search Bar -->
    <form method="get" action="<?=site_url('/auth/dashboard')?>" class="mb-4 flex justify-end gap-2">
      <input 
        type="text" 
        name="q" 
        value="<?=html_escape($_GET['q'] ?? '')?>" 
        placeholder="Search student..." 
        class="px-4 py-2 border-2 border-purple-300 rounded-l-2xl focus:outline-none focus:ring-2 focus:ring-purple-500 w-64 bg-purple-100 placeholder-gray-500">
      <button type="submit" 
              class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-r-2xl shadow transition-all duration-300">
        <i class="fa fa-search"></i>
      </button>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto rounded-2xl border-4 border-purple-600">
      <table class="w-full text-center border-collapse">
        <thead>
          <tr class="bg-gradient-to-r from-purple-800 to-purple-600 text-purple-100 uppercase tracking-wider hp-title text-lg">
            <th class="py-3 px-4">ID</th>
            <th class="py-3 px-4">Lastname</th>
            <th class="py-3 px-4">Firstname</th>
            <th class="py-3 px-4">Email</th>
          </tr>
        </thead>
        <tbody class="text-gray-900 text-sm">
          <?php if(!empty($users)): ?>
            <?php foreach(html_escape($users) as $user): ?>
              <tr class="hover:bg-purple-200 transition duration-200">
                <td class="py-3 px-4 font-medium"><?=($user['id']);?></td>
                <td class="py-3 px-4"><?=($user['lname']);?></td>
                <td class="py-3 px-4"><?=($user['fname']);?></td>
                <td class="py-3 px-4"><?=($user['email']);?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" class="py-4 text-gray-600">No students found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination + Logout -->
    <div class="mt-4 flex justify-between items-center">
      <div class="pagination flex space-x-2">
        <?php
          if (!empty($page)) {
            echo str_replace(
              ['<a ', '<strong>', '</strong>'],
              [
                '<a class="hp-page"',
                '<span class="hp-current">',
                '</span>'
              ],
              $page
            );
          }
        ?>
      </div>

      <a href="<?=site_url('auth/logout');?>"
         class="btn-hover bg-purple-700 hover:bg-purple-900 text-purple-100 px-4 py-2 rounded-2xl shadow flex items-center gap-2">
         <i class="fa-solid fa-right-from-bracket"></i> Logout
      </a>
    </div>

  </div>
</div>

</body>
</html>
