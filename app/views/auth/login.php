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
</head>
<body class="bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-sm border-2 border-purple-300">
    <h1 class="text-2xl font-bold text-center text-purple-800 mb-6">Login</h1>
    
    <form method="post" class="space-y-4">
        <input type="text" name="username" placeholder="Username" required
               class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        
        <input type="password" name="password" placeholder="Password" required
               class="w-full px-4 py-3 border border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        
        <button type="submit" 
                class="w-full py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition duration-300">
            Login
        </button>
    </form>
    
    <p class="text-center text-gray-600 mt-4">
        Don't have an account? 
        <a href="<?= site_url('/') ?>" class="text-purple-600 font-semibold hover:underline">Register</a>
    </p>
</div>

</body>
</html>
