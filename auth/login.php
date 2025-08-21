<?php
require_once __DIR__ . '/../config.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username']);
    $p = trim($_POST['password']);

    if ($u !== '' && $p !== '') {
        $stmt = $mysqli->prepare('SELECT user_id, username, password, role FROM users WHERE username = ?');
        $stmt->bind_param('s', $u);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            if ($p === $row['password']) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
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
<?php include BASE_PATH . '/partials/nav.php'; ?>

<div class="container">
    <div class="login-form">
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
