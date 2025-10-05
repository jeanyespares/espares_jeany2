<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Student Directory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffe4e6, #fce7f3, #ede9fe, #dbeafe);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .font-title {
            font-family: 'Dancing Script', cursive;
        }
        .btn-hover:hover {
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(236, 72, 153, 0.5);
        }
    </style>
</head>
<body>

<?php 
// Kukunin ang Flashdata messages mula sa Session, hindi na kailangan ang $data check
$error_message = $this->session->flashdata('error');
$success_message = $this->session->flashdata('success');
?>

<div class="w-full max-w-sm bg-white shadow-2xl rounded-3xl p-8 border-4 border-pink-200">
    <h2 class="text-3xl font-title text-center mb-6 text-fuchsia-600 flex items-center justify-center gap-2">
        <i class="fa-solid fa-lock"></i> Admin Login
    </h2>
    
    <?php if ($success_message): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 rounded-lg" role="alert">
            <p class="font-bold">Success!</p>
            <p><?= html_escape($success_message); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded-lg" role="alert">
            <p class="font-bold">Login Error</p>
            <p><?= html_escape($error_message); ?></p>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('users/login'); ?>" class="space-y-4">
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                placeholder="jeany" 
                required 
                class="mt-1 block w-full px-4 py-2 border-2 border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-fuchsia-400 bg-pink-50 placeholder-gray-400 transition duration-300">
        </div>
        
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                placeholder="jeany123" 
                required 
                class="mt-1 block w-full px-4 py-2 border-2 border-pink-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-fuchsia-400 bg-pink-50 placeholder-gray-400 transition duration-300">
        </div>
        
        <button type="submit" class="btn-hover inline-flex items-center justify-center gap-2 bg-gradient-to-r from-fuchsia-500 to-purple-600 text-white font-bold px-5 py-2.5 rounded-xl shadow-lg transition-all duration-300 w-full">
            <i class="fa-solid fa-sign-in-alt"></i> Secure Login
        </button>
    </form>
    
    <div class="link mt-4 text-center">
        <p class="text-sm text-gray-500">
            <a href="<?= site_url('users/index'); ?>" class="text-pink-600 hover:text-pink-800 font-semibold transition duration-200">
                ← Go back to Student Directory
            </a>
        </p>
    </div>
</div>

</body>
</html>