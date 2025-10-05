<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------
 * URI ROUTING
 * ------------------------------------------------------------------
 * Defines all routes for Student Directory (CRUD) and User Authentication.
 */

// ===============================================
// CORE CRUD ROUTES (Student Directory)
// ===============================================

// INDEX/HOME: Main student directory list (Root URL)
$router->get('/', 'UsersController::index'); 

// CREATE: Handles both GET (form display) and POST (submission)
$router->match('/users/create', 'UsersController::create', ['GET', 'POST']);

// UPDATE: Handles both GET (form display) and POST (submission)
$router->match('/users/update/{id}', 'UsersController::update', ['GET', 'POST']);

// DELETE
$router->get('/users/delete/{id}', 'UsersController::delete');

// Fallback index route
$router->get('/users/index', 'UsersController::index'); 

// ===============================================
// AUTHENTICATION & AUTHORIZATION ROUTES
// ===============================================

// LOGIN: Handles both GET (form display) and POST (authentication logic)
$router->match('/users/login', 'UsersController::login', ['GET', 'POST']); 

// REGISTER: Handles both GET (form display) and POST (user creation logic)
$router->match('/users/register', 'UsersController::register', ['GET', 'POST']);

// LOGOUT
$router->get('/users/logout', 'UsersController::logout'); 

// DASHBOARD (Protected Area - still needed for the link in index.php)
$router->get('/users/dashboard', 'UsersController::dashboard');

// ADMIN ONLY (Protected Area Example)
$router->get('/users/admin_only', 'UsersController::admin_only');
