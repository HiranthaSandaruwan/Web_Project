<?php
require_once __DIR__ . '/../config.php';
$page_title = "Login";

$msg = '';


// Detect if new schema column `last_login` exists (graceful fallback if DB not migrated yet)
$hasLastLogin = false;
if ($check = $mysqli->query("SHOW COLUMNS FROM users LIKE 'last_login'")) {
    $hasLastLogin = $check->num_rows > 0;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username']);
    $p = trim($_POST['password']);

    if ($u !== '' && $p !== '') {
            // Choose query depending on schema availability
            if ($hasLastLogin) {
                $stmt = $mysqli->prepare('SELECT user_id, username, password, role, last_login FROM users WHERE username = ?');
            } else {
                $stmt = $mysqli->prepare('SELECT user_id, username, password, role FROM users WHERE username = ?');
            }
        $stmt->bind_param('s', $u);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            // Support both plain text passwords (old) and hashed passwords (new)
            $passwordValid = false;
            if (password_verify($p, $row['password'])) {
                // New hashed password
                $passwordValid = true;
            } elseif ($p === $row['password']) {
                // Old plain text password - for backwards compatibility
                $passwordValid = true;
            }
            
            if ($passwordValid) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                    if ($hasLastLogin) {
                        // store last login for notification count then update
                        $_SESSION['prev_login'] = $row['last_login'];
                        $up = $mysqli->prepare('UPDATE users SET last_login = NOW() WHERE user_id=?');
                        $up->bind_param('i', $row['user_id']);
                        $up->execute();
                    } else {
                        // No column yet; notifications feature disabled until migration
                        $_SESSION['prev_login'] = null;
                    }
                if ($row['role'] === 'admin') {
                    header('Location: ' . url('admin/index.php'));
                } else {
                    header('Location: ' . url('index.php'));
                }
                exit;
            } else {
                $msg = 'Wrong password';
            }
        } else {
            $msg = 'User not found';
        }
    } else {
        $msg = 'Enter username and password';
    }
}
?>
<?php include BASE_PATH . '/partials/header.php'; ?>

<div class="container">
    <div style="max-width: 400px; margin: 0 auto;">
        <h2>Login to Hardware Tracker</h2>
        <p class="small">Use your credentials to access the repair tracking system</p>
        
        <?php if ($msg) { echo '<div class="alert">' . htmlspecialchars($msg) . '</div>'; } ?>
        
        <form method="post">
            <div class="form-row">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Enter your username">
            </div>

            <div class="form-row">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter your password">
            </div>

            <button type="submit" class="btn-primary">Login</button>
        </form>
        
        <div class="login-help mt">
            <p class="small">
                <strong>Demo Accounts:</strong><br>
                User: uoc / uoc<br>
                Admin: admin / admin
            </p>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
