<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Directory</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="<?=base_url();?>public/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">

  <!-- Navbar -->
  <nav class="bg-gradient-to-r from-blue-600 to-cyan-400 shadow-lg">
    <div class="max-w-7xl mx-auto px-6 py-4">
      <!-- Wala nang title dito -->
    </div>
  </nav>

  <!-- Main Content -->
  <div class="max-w-6xl mx-auto mt-10 px-4">
    <div class="bg-white shadow-2xl rounded-2xl p-6 border border-gray-200">
      
      <!-- Add New User Button (Upper Right Corner) -->
      <div class="flex justify-end mb-6">
        <a href="<?=site_url('users/create')?>"
           class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold px-5 py-2 rounded-full shadow-md transition-all duration-300 hover:scale-105">
          <i class="fa-solid fa-user-plus"></i> Add New User
        </a>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto rounded-2xl border border-gray-200 shadow">
        <table class="w-full text-center border-collapse">
          <thead>
            <tr class="bg-gradient-to-r from-blue-600 to-cyan-400 text-white text-sm uppercase tracking-wide">
              <th class="py-3 px-4">ID</th>
              <th class="py-3 px-4">Lastname</th>
              <th class="py-3 px-4">Firstname</th>
              <th class="py-3 px-4">Email</th>
              <th class="py-3 px-4">Action</th>
            </tr>
          </thead>
          <tbody class="text-gray-700 text-sm">
            <?php foreach(html_escape($users) as $user): ?>
              <tr class="hover:bg-blue-50 transition duration-200">
                <td class="py-3 px-4 font-medium"><?=($user['id']);?></td>
                <td class="py-3 px-4"><?=($user['lname']);?></td>
                <td class="py-3 px-4"><?=($user['fname']);?></td>
                <td class="py-3 px-4">
                  <span class="bg-blue-100 text-blue-700 text-sm font-semibold px-3 py-1 rounded-full">
                    <?=($user['email']);?>
                  </span>
                </td>
                <td class="py-3 px-4 flex justify-center gap-3">
                  <!-- Update Button -->
                  <a href="<?=site_url('users/update/'.$user['id']);?>"
                     class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-lg shadow flex items-center gap-1 transition duration-200">
                    <i class="fa-solid fa-pen-to-square"></i> Update
                  </a>
                  <!-- Delete Button -->
                  <a href="<?=site_url('users/delete/'.$user['id']);?>"
                     class="inline-flex items-center gap-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-3 py-1 rounded-lg shadow transition-all duration-300">
                     <i class="fa-solid fa-trash"></i> Delete
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>

</body>
</html>
