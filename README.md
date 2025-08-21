Hardware Repair Request Tracker

A simple web app for students and staff to submit hardware repair requests, and for admins to manage them.
Built entirely with HTML, CSS, JavaScript, PHP, and MySQL (no frameworks, libraries, or external APIs).
✅ Project Rules & Requirements

    Must use pure HTML, CSS, JS, PHP, MySQL

    No frameworks, external libraries, or APIs

    Only basic form validation (no advanced security, no hashing, no CSRF)

    Roles: Admin and User

    Unauthorized users cannot access protected pages

    Required pages: Login, Home, Admin, Functionalities, Help

👥 System Roles & Functions
1. User (students/staff)

    Login/Logout

    Submit repair requests (device type, model, serial no, description, priority)

    View My Requests (list only their own requests)

    View Request Details (status and info)

    Help Page (how to use the system)

👉 Users cannot see or manage other people’s requests.
2. Admin

    Login/Logout

    Dashboard (overview of system functions)

    Manage Users

        Add a new user

        Edit user details or role

        Delete a user (except themselves)

    Manage Requests

        View all requests from all users

        Update request status (Pending, In Progress, Completed, Rejected)

        Change request priority

        Assign due dates

    Reports (basic tables only)

        Requests by status (Pending/Completed/etc.)

        Requests by device type

        Requests per user

        Requests created this month

        Overdue requests (if due dates are set)

    Help Page

👉 Admin has full control of the system.
📂 Folder Structure

/ (document root)
  /assets
    /css/styles.css
    /js/app.js
  /auth
    login.php
    logout.php
  /admin
    index.php
    users.php
    requests.php
    request_view.php
    reports.php
  /partials
    header.php
    nav.php
    footer.php
  db.php
  index.php             # Home
  features.php          # Functionalities
  help.php              # Help / How-to
  request_new.php       # User: create request
  my_requests.php       # User: list own requests
  request_view.php      # User: view request details

🗄 Database Setup

CREATE DATABASE repair_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE repair_tracker;

-- Users
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Requests
CREATE TABLE requests (
  request_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  device_type VARCHAR(100) NOT NULL,
  model VARCHAR(100),
  serial_no VARCHAR(100),
  priority ENUM('Low','Medium','High') DEFAULT 'Medium',
  description TEXT NOT NULL,
  status ENUM('Pending','In Progress','Completed','Rejected') DEFAULT 'Pending',
  due_date DATE NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

Default Users

INSERT INTO users (username, password, role) VALUES ('uoc', 'uoc', 'user');
INSERT INTO users (username, password, role) VALUES ('admin', 'admin', 'admin');

🔌 Database Connection

<?php
// db.php
$dbhost='localhost';
$dbuser='root';
$dbpass='2323';
$dbname='repair_tracker';

$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($mysqli->connect_errno) {
  die('DB Connect failed: '.$mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
?>

Include in pages with:

<?php require_once __DIR__ . '/db.php'; ?>

▶️ How to Run

    Install XAMPP/WAMP/LAMP

    Place this folder inside htdocs/ (XAMPP) or web root

    Start Apache & MySQL

    Import SQL from section above

    Update db.php with your DB credentials

    Open: http://localhost/your-folder/auth/login.php

    Login with:

        User → uoc / uoc

        Admin → admin / admin

👥 Credits

Built by <Your Group Name/Numbers>
University group project — fully hand-coded, without frameworks or external libraries.