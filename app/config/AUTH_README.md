Authentication notes
====================

This project now includes a simple authentication and role-based authorization system.

Expected users table (MySQL example):

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Create an admin user (quick):

1. Use the included PHP helper script from repository root (PowerShell example):

```powershell
php scripts\create_admin_mysql.php adminusername StrongPassword123
```

2. The script reads DB connection info from `app/config/database.php` (the `main` connection).

Notes & security:
- Passwords are stored using PHP's password_hash() and verified with password_verify().
- The `StudentsController::index()` action is protected and requires role `admin`. Add the `require_role('admin')` or change role checks as needed.
- Consider adding CSRF protection and account management UIs for production usage.
Authentication and Authorization notes

1) Database table

Create a `users` table with at least these columns:

- id INT PRIMARY KEY AUTO_INCREMENT
- username VARCHAR(100) UNIQUE NOT NULL
- password VARCHAR(255) NOT NULL   -- store password_hash()
- role VARCHAR(50) DEFAULT 'user'

2) Creating an admin user (PHP example)

Use this script once to insert an admin user. Replace DB access with your project's DB tool.

<?php
$hash = password_hash('YourAdminPasswordHere', PASSWORD_DEFAULT);
// INSERT INTO users (username, password, role) VALUES ('admin', '$hash', 'admin');
?>

3) Behavior

- Login is available at /login
- After login, protected pages call Auth->require_login() and Auth->require_role('admin')
- Session uses the framework Session library
