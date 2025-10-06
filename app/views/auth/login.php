<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #ffe4e6, #fce7f3, #ede9fe, #dbeafe); }
    .font-title { font-family: 'Dancing Script', cursive; }
    .btn-hover:hover { transform: scale(1.07) rotate(-1deg); box-shadow: 0 0 15px #ff99cc, 0 0 25px #ffccff; }
</style>
</head>
<body class="min-h-screen flex items-center justify-center">

<div class="bg-white shadow-2xl rounded-3xl p-8 w-full max-w-sm border-4 border-pink-200 animate-fadeIn">
    <h1 class="text-2xl font-title text-center text-pink-600 mb-6">Login</h1>
    
    <form method="post" class="space-y-5">
        <input type="text" name="username" placeholder="Username" required
               class="w-full px-4 py-3 bg-pink-50 text-gray-700 border-2 border-pink-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-pink-400 shadow-sm transition duration-200">
        
        <input type="password" name="password" placeholder="Password" required
               class="w-full px-4 py-3 bg-pink-50 text-gray-700 border-2 border-pink-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-pink-400 shadow-sm transition duration-200">
        
        <button type="submit" 
                class="w-full py-3 bg-gradient-to-r from-pink-400 via-fuchsia-500 to-purple-500 hover:from-pink-500 hover:via-fuchsia-600 hover:to-purple-600 text-white font-bold rounded-2xl shadow-lg transition duration-300 transform hover:scale-105 flex justify-center items-center gap-2">
            <i class="fa-solid fa-right-to-bracket"></i> Login
        </button>
    </form>
    
    <p class="text-center text-gray-600 mt-4">
        Don't have an account? 
        <a href="<?= site_url('/') ?>" class="text-pink-600 font-semibold hover:underline">Register</a>
    </p>
</div>

</body>
</html>
