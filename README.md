Hardware Repair Request Tracker

A simple web app for students and staff to submit hardware repair requests. Admins can view and manage all requests. Built using ONLY: HTML, CSS, JavaScript, PHP, and MySQL (no frameworks, libraries, or external APIs) per project rules.

✅ Project Rules (Still Enforced)
- Pure technologies only (no Laravel, Bootstrap, jQuery, etc.)
- No external APIs
- Basic validation only (no CSRF tokens, no advanced security)
- Plain‑text passwords (educational requirement; NOT secure for production)
- Two roles: admin, user
- Protected pages require login

⚠️ Security Notice
Passwords are stored in plain text because the brief explicitly disallows hashing/security layers. Do NOT deploy this version to a public/real environment. To harden later: add password_hash(), sessions hardening, CSRF tokens, input sanitization layers.

🎨 UI / Frontend Status
- Single consolidated stylesheet: `assets/css/unified-styles.css` (replaced multiple old CSS files)
- Light & Dark theme toggle (stored in localStorage)
- Modern fixed sidebar navigation (icons + grouped sections)
- Responsive layout (mobile sidebar overlay)
- Simplified validation: only error messages (no green "looks good")

🧭 Navigation
Sidebar items adapt to role. The legacy `partials/nav.php` exists for backward compatibility but the new sidebar is rendered inside `partials/header.php`.

👥 Current Feature Matrix
User:
- Login / Logout
- Submit new repair request
- View only their own requests
- View request details (status, priority, description)
- Help & Features pages

Admin:
- Login / Logout
- Admin dashboard (overview widgets / navigation)
- View all requests
- Update status, priority, due date (where implemented in forms)
- Add users (choose role at creation)
- Delete users (cannot delete themselves)
- Basic reports page (tables)
- Help & Features pages

Differences vs Original Plan:
- User edit/role change after creation: NOT implemented (only set on create)
- Password hashing: intentionally removed (plain text per requirements)
- Additional internal tables (history, comments) prepared but UI integration may be partial

🗄 Database Schema (Current)
Full schema is in `database.sql`. Summary of main tables below.

users
- user_id INT PK AUTO_INCREMENT
- username VARCHAR(50) UNIQUE
- password VARCHAR(255) (plain text currently)
- role ENUM('admin','user')
- last_login TIMESTAMP NULL
- created_at TIMESTAMP

requests
- request_id INT PK
- user_id FK -> users
- device_type, model, serial_no
- priority ENUM('Low','Medium','High')
- category ENUM('Hardware Failure','Software Issue','Physical Damage','Other')
- description TEXT
- status ENUM('Pending','In Progress','Completed','Rejected')
- due_date DATE NULL
- created_at / updated_at timestamps

comments (for request discussion – backend tables ready)
- comment_id PK
- request_id FK -> requests
- user_id FK -> users
- comment_text, admin_only (0/1)
- created_at

request_history (audit of field changes)
- history_id PK
- request_id FK -> requests
- field_changed, old_value, new_value
- changed_at

Seed Users
INSERT INTO users (username,password,role) VALUES ('uoc','uoc','user');
INSERT INTO users (username,password,role) VALUES ('admin','admin','admin');

� Folder Structure (Current Key Files)
/assets
  /css/unified-styles.css  (all styling + themes)
  /js/app.js               (theme toggle, sidebar, validation)
/auth
  login.php, logout.php
/admin
  index.php, users.php, requests.php, request_view.php, reports.php
/partials
  header.php (includes sidebar) / nav.php (legacy) / footer.php
config.php (wraps paths + includes db)
db.php (DB connection)
database.sql (full schema & seed data)
index.php, features.php, help.php
request_new.php, my_requests.php, request_view.php

🔌 Database Connection Example (`db.php`)
```
$dbhost='localhost';
$dbuser='root';
$dbpass='2323';
$dbname='repair_tracker';
$mysqli = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
if($mysqli->connect_errno){ die('DB Connect failed: '.$mysqli->connect_error); }
$mysqli->set_charset('utf8mb4');
```

▶️ Setup & Run
1. Install XAMPP/WAMP (or LAMP on Linux)
2. Place project folder inside `htdocs/` (XAMPP) or web root
3. Create DB and import `database.sql`
4. Adjust credentials in `db.php` if needed
5. Visit: `http://localhost/Web_Project/auth/login.php`
6. Login using:
   - User: `uoc / uoc`
   - Admin: `admin / admin`

🌗 Theme Usage
- Toggle button in sidebar footer switches Light/Dark
- Preference stored in browser localStorage (`theme` key)
Shortcut: (Ctrl + D) may toggle theme (depends on JS event map)

🚧 Known Limitations / Next Steps
- No password hashing (intentional for brief) – add later for security
- No user edit UI yet (only create/delete)
- Comments and history tables not fully surfaced in UI
- Minimal server-side validation

🛠 Possible Future Improvements (Optional)
- Add password hashing & migration script
- Implement inline request history & comment threads UI
- Add pagination & search for requests
- Role-based edit of user accounts (change role/password reset)
- Export reports (CSV)

👥 Credits
Built by <Your Group Name/Numbers>
University group project — fully hand-coded, without frameworks or external libraries.

---
Educational prototype – not production hardened.