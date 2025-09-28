<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Directory</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600&family=Pacifico&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?=base_url();?>/public/style.css">

  <style>
    body { 
      font-family: 'Baloo 2', cursive; 
      background: linear-gradient(135deg, #ffe4e6, #fce7f3, #ede9fe, #dbeafe);
    }
    .font-title { font-family: 'Pacifico', cursive; }
    .btn-hover:hover { 
      transform: scale(1.07) rotate(-1deg); 
      box-shadow: 0 0 15px #ff99cc, 0 0 25px #ffccff; 
    }
    table thead tr { background: linear-gradient(90deg, #f472b6, #ec4899, #d946ef); }
    .hp-page { 
      padding: 6px 12px; 
      background: #f9a8d4; 
      border-radius: 9999px; 
      color: white; 
      font-weight: bold; 
      transition: 0.3s; 
    }
    .hp-page:hover { background: #f472b6; transform: scale(1.1); }
    .hp-current { 
      padding: 6px 12px; 
      background: #d946ef; 
      border-radius: 9999px; 
      color: white; 
      font-weight: bold; 
    }
  </style>
</head>
<body class="min-h-screen">

  <!-- Header -->
  <nav class="bg-gradient-to-r from-pink-400 via-fuchsia-500 to-purple-500 shadow-lg border-b-4 border-pink-300">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <h1 class="text-white font-title text-3xl flex items-center gap-2">
        <i class="fa-solid fa-sparkles"></i> Student Directory 💖
      </h1>
    </div>
  </nav>

  <!-- Content -->
  <div class="max-w-6xl mx-auto mt-10 px-4">
    <div class="bg-white shadow-2xl rounded-3xl p-6 border-4 border-pink-200">

      <!-- Top Actions -->
      <div class="flex justify-between items-center mb-6 flex-wrap gap-4">

        <!-- Search Bar -->
        <form method="get" action="<?=site_url()?>" class="flex">
          <input 
            type="text" 
            name="q" 
            value="<?=html_escape($_GET['q'] ?? '')?>" 
            placeholder="🔍 Search student..." 
            class="px-4 py-2 border-2 border-pink-300 rounded-l-2xl focus:outline-none focus:ring-2 focus:ring-pink-400 w-64 bg-pink-50 placeholder-gray-400">
          <button type="submit" class="bg-pink-400 hover:bg-pink-500 text-white px-4 py-2 rounded-r-2xl shadow transition-all duration-300">
            <i class="fa fa-search"></i>
          </button>
        </form>

        <!-- Add Button -->
        <a href="<?=site_url('users/create')?>"
           class="btn-hover inline-flex items-center gap-2 bg-gradient-to-r from-pink-400 to-fuchsia-500 text-white font-bold px-5 py-2 rounded-2xl shadow-md transition-all duration-300">
          <i class="fa-solid fa-user-plus"></i> Add Student
        </a>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto rounded-3xl border-4 border-pink-200 shadow-md">
        <table class="w-full text-center border-collapse">
          <thead>
            <tr class="text-white uppercase tracking-wider text-lg">
              <th class="py-3 px-4">ID</th>
              <th class="py-3 px-4">Lastname</th>
              <th class="py-3 px-4">Firstname</th>
              <th class="py-3 px-4">Email</th>
              <th class="py-3 px-4">Action</th>
            </tr>
          </thead>
          <tbody class="text-gray-700 text-base">
            <?php if(!empty($users)): ?>
              <?php foreach(html_escape($users) as $user): ?>
                <tr class="hover:bg-pink-100 transition duration-200">
                  <td class="py-3 px-4 font-medium"><?=($user['id']);?></td>
                  <td class="py-3 px-4"><?=($user['fname']);?></td>
                  <td class="py-3 px-4"><?=($user['lname']);?></td>
                  <td class="py-3 px-4"><?=($user['email']);?></td>
                  <td class="py-3 px-4 flex justify-center gap-3">
                    <a href="<?=site_url('users/update/'.$user['id']);?>"
                       class="btn-hover bg-green-400 hover:bg-green-500 text-white px-3 py-1 rounded-xl shadow flex items-center gap-1">
                      <i class="fa-solid fa-pen-to-square"></i> Update
                    </a>
                    <a href="<?=site_url('users/delete/'.$user['id']);?>"
                       class="btn-hover bg-red-400 hover:bg-red-500 text-white px-3 py-1 rounded-xl shadow flex items-center gap-1">
                      <i class="fa-solid fa-trash"></i> Delete
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="5" class="py-4 text-gray-500">No students found 😿</td></tr>
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
