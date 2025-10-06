<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #ffe4e6, #fce7f3, #ede9fe, #dbeafe);
    }
    .font-title {
        font-family: 'Dancing Script', cursive;
    }
    .btn-hover:hover {
        transform: scale(1.07) rotate(-1deg);
        box-shadow: 0 0 15px #ff99cc, 0 0 25px #ffccff;
    }
</style>
</head>
<body class="min-h-screen flex items-center justify-center">

<div class="bg-white shadow-2xl rounded-3xl p-8 w-full max-w-sm border-4 border-pink-200 animate-fadeIn">
    <h1 class="text-2xl font-title text-center text-pink-600 mb-6">Create Account</h1>
    
    <form method="post" class="space-y-5">
        <input type="text" name="username" placeholder="Username" required
               class="w-full px-4 py-3 bg-pink-50 text-gray-700 border-2 border-pink-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-pink-400 shadow-sm transition duration-200">
        
        <input type="password" name="password" placeholder="Password" required
               class="w-full px-4 py-3 bg-pink-50 text-gray-700 border-2 border-pink-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-pink-400 shadow-sm transition duration-200">
        
        <select name="role"
                class="w-full px-4 py-3 bg-pink-50 text-gray-700 border-2 border-pink-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-pink-400 shadow-sm transition duration-200">
            <option value="user">User</option>
            <?php if (!$admin_exists): ?>
                <option value="admin">Admin</option>
            <?php endif; ?>
        </select>

        <?php if ($admin_exists): ?>
            <p class="text-sm text-gray-500 italic text-center">
                Admin account already exists. You can only register as a user.
            </p>
        <?php endif; ?>
        
        <button type="submit"
                class="w-full py-3 bg-gradient-to-r from-pink-400 via-fuchsia-500 to-purple-500 hover:from-pink-500 hover:via-fuchsia-600 hover:to-purple-600 text-white font-bold rounded-2xl shadow-lg transition duration-300 transform hover:scale-105 flex justify-center items-center gap-2">
            <i class="fa-solid fa-user-plus"></i> Register
        </button>
    </form>
    
    <p class="text-center text-gray-600 mt-4">
        Already have an account?
        <a href="<?= site_url('auth/login') ?>" class="text-pink-600 font-semibold hover:underline">Login</a>
    </p>
</div>

</body>
</html>