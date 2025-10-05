<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------
*/

// ===============================================
// AUTHENTICATION & AUTHORIZATION ROUTES
// ===============================================

// 1. LOGIN
// GET: Maglo-load ng login form (tatawagin ang UsersController::login() sa GET request)
$router->get('/users/login', 'UsersController::login'); 
// POST: Magpo-process ng form submission (tatawagin ang UsersController::login() sa POST request)
// Note: Sa Controller mo, ang iisang login() method ang humahawak sa GET at POST.
$router->post('/users/login', 'UsersController::login'); 

// 2. REGISTER
// GET: Maglo-load ng register form (tatawagin ang UsersController::register() sa GET request)
$router->get('/users/register', 'UsersController::register');
// POST: Magpo-process ng form submission (tatawagin ang UsersController::register() sa POST request)
// Note: Sa Controller mo, ang iisang register() method ang humahawak sa GET at POST.
$router->post('/users/register', 'UsersController::register'); 

// 3. LOGOUT
$router->get('/users/logout', 'UsersController::logout');

// 4. PROTECTED PAGES
// DASHBOARD: Ito ang pupuntahan after successful login
$router->get('/users/dashboard', 'UsersController::dashboard');
// ADMIN ONLY: Sample admin route
$router->get('/users/admin_only', 'UsersController::admin_only');

// ===============================================
// ORIGINAL CRUD ROUTES (Student Directory)
// ===============================================

// INDEX/HOME: Ito ang Student Directory (Users List)
// Pinalitan ko ang '/' route para hindi ito ang default, para makita mo ang login.
// Pero kung gusto mo pa rin itong maging home, pwede mo siyang ibalik sa $router->get('/', 'UsersController::index');
$router->get('/users/index', 'UsersController::index');
// Ginamit ko ang root route (/) para mag-redirect sa login page.
$router->get('/', 'UsersController::login'); // **NEW: Redirect root to login**

// CREATE
$router->match('/users/create', 'UsersController::create', ['GET', 'POST']);

// UPDATE
$router->match('/users/update/{id}', 'UsersController::update', ['GET', 'POST']);

// DELETE
$router->get('/users/delete/{id}', 'UsersController::delete');