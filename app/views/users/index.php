<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student List</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?=base_url();?>/public/style.css">

  <style>
    body { 
      font-family: 'Inter', sans-serif; 
      background: #f7fafc; /* light gray professional bg */
      color: #2d3748; 
    }
    table thead tr { background: #2b6cb0; } /* deep blue header */
    .hp-page { 
      padding: 6px 12px; 
      background: #2b6cb0; 
      border-radius: 6px; 
      color: white; 
      font-weight: 600; 
      transition: 0.2s; 
    }
    .hp-page:hover { background: #2c5282; transform: translateY(-2px); }
    .hp-current { 
      padding: 6px 12px; 
      background: #3182ce; 
      border-radius: 6px; 
      color: white; 
      font-weight: 700; 
    }
    .btn-hover:hover { 
      transform: translateY(-2px); 
      box-shadow: 0 4px 12px rgba(0,0,0,0.2); 
    }
  </style>
</head>
<body class="min-h-screen">

  <!-- Header -->
  <?php $title = 'Student List'; include __DIR__ . '/../partials/header.php'; ?>

  <!-- Content -->
  <div class="max-w-6xl mx-auto mt-10 px-4">
    <div class="bg-white shadow-xl rounded-lg p-6 border border-gray-200">

      <!-- Top Actions -->
      <div class="flex justify-between items-center mb-6 flex-wrap gap-4">

        <!-- Search Bar -->
        <form method="get" action="<?=site_url()?>" class="flex">
          <input 
            type="text" 
            name="q" 
            value="<?=html_escape($_GET['q'] ?? '')?>" 
            placeholder="Search student..." 
            class="px-4 py-2 border border-gray-400 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64 bg-white placeholder-gray-400">
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-lg shadow transition">
            <i class="fa fa-search"></i>
          </button>
        </form>

        <!-- Add Button (admin only) -->
        <?php if (!empty($current_user) && ($current_user['role'] ?? '') === 'admin'): ?>
        <a href="<?=site_url('users/create')?>"
           class="btn-hover inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg shadow transition">
          <i class="fa-solid fa-user-plus"></i> Add Student Record
        </a>
        <?php endif; ?>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto rounded-lg border border-gray-200 shadow">
        <table class="w-full text-center border-collapse">
          <thead>
            <tr class="text-white uppercase tracking-wide text-sm">
              <th class="py-3 px-4">ID</th>
              <th class="py-3 px-4">First Name</th>
              <th class="py-3 px-4">Last Name</th>
              <th class="py-3 px-4">Email</th>
              <th class="py-3 px-4">Action</th>
            </tr>
          </thead>
          <tbody class="text-gray-700 text-sm">
            <?php if(!empty($users)): ?>
              <?php foreach(html_escape($users) as $user): ?>
                <tr class="hover:bg-gray-100 transition">
                  <td class="py-3 px-4 font-medium"><?=($user['id']);?></td>
                  <td class="py-3 px-4"><?=($user['fname']);?></td>
                  <td class="py-3 px-4"><?=($user['lname']);?></td>
                  <td class="py-3 px-4"><?=($user['email']);?></td>
                  <td class="py-3 px-4 flex justify-center gap-3">
                    <?php if (!empty($current_user) && ($current_user['role'] ?? '') === 'admin'): ?>
                    <a href="<?=site_url('users/update/'.$user['id']);?>"
                       class="btn-hover bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md shadow flex items-center gap-1">
                      <i class="fa-solid fa-pen-to-square"></i> Update
                    </a>
                    <a href="<?=site_url('users/delete/'.$user['id']);?>"
                       class="btn-hover bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md shadow flex items-center gap-1">
                      <i class="fa-solid fa-trash"></i> Delete
                    </a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="5" class="py-4 text-gray-500">No students found</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-6 flex justify-center">
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
      </div>

    </div>
  </div>

</body>
</html>
