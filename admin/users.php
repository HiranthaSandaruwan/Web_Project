<?php
require_once __DIR__ . '/../config.php';
$page_title = "User Management";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . url('auth/login.php'));
    exit;
}

$msg = '';

// Add user
if (isset($_POST['add'])) {
    $u = trim($_POST['username']);
    $p = trim($_POST['password']);
    $r = $_POST['role'];
    if ($u !== '' && $p !== '' && strlen($p) >= 3) {
        // Check if username already exists
        $checkUser = $mysqli->prepare('SELECT user_id FROM users WHERE username = ?');
        $checkUser->bind_param('s', $u);
        $checkUser->execute();
        $result = $checkUser->get_result();
        
        if ($result->num_rows > 0) {
            $msg = '<div class="alert alert-error">Username already exists. Please choose a different username.</div>';
        } else {
            // Store password as plain text (no encryption)
            $stmt = $mysqli->prepare('INSERT INTO users (username, password, role) VALUES (?,?,?)');
            $stmt->bind_param('sss', $u, $p, $r);
            if ($stmt->execute()) {
                $msg = '<div class="alert alert-success">✅ User "' . htmlspecialchars($u) . '" added successfully!</div>';
            } else {
                $msg = '<div class="alert alert-error">❌ Error adding user. Please try again.</div>';
            }
        }
    } else {
        $msg = '<div class="alert alert-error">❌ Please fill all fields. Password must be at least 3 characters.</div>';
    }
}

// Delete user (not self)
if (isset($_GET['del'])) {
    $delId = (int) $_GET['del'];
    if ($delId !== $_SESSION['user_id']) {
        $mysqli->query('DELETE FROM users WHERE user_id=' . $delId);
    }
}

// Delete user (not self)
if (isset($_GET['del'])) {
    $delId = (int) $_GET['del'];
    if ($delId !== $_SESSION['user_id']) {
        $stmt = $mysqli->prepare('DELETE FROM users WHERE user_id = ?');
        $stmt->bind_param('i', $delId);
        if ($stmt->execute()) {
            $msg = '<div class="alert alert-success">✅ User deleted successfully!</div>';
        } else {
            $msg = '<div class="alert alert-error">❌ Error deleting user.</div>';
        }
    } else {
        $msg = '<div class="alert alert-warning">⚠️ You cannot delete your own account!</div>';
    }
}

$users = $mysqli->query('SELECT * FROM users ORDER BY user_id');
?>
<?php include BASE_PATH . '/partials/header.php'; ?>

<div class="container">
    <div class="card-header">
        <h1 class="card-title">👥 User Management</h1>
        <p class="card-subtitle">Add new users and manage existing accounts</p>
    </div>

    <?php if ($msg) { echo $msg; } ?>

    <!-- Add New User Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">➕ Add New User</h3>
        </div>
        
        <form method="post" data-validate>
            <input type="hidden" name="add" value="1">
            
            <div class="form-group">
                <label for="username">👤 Username</label>
                <input type="text" id="username" name="username" required data-label="Username" 
                       placeholder="Enter username (minimum 2 characters)" minlength="2">
                <span class="form-help">Choose a unique username for the new user</span>
            </div>

            <div class="form-group">
                <label for="password">🔒 Password</label>
                <input type="password" id="password" name="password" required data-label="Password" 
                       placeholder="Enter password (minimum 3 characters)" minlength="3">
                <span class="form-help">Password should be secure and at least 3 characters long</span>
            </div>

            <div class="form-group">
                <label for="role">🔧 User Role</label>
                <select id="role" name="role" required>
                    <option value="user" selected>👤 Regular User</option>
                    <option value="admin">🛡️ Administrator</option>
                </select>
                <span class="form-help">Choose the appropriate role for this user</span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <span>➕</span> Add User
                </button>
                <button type="reset" class="btn btn-secondary">
                    <span>🔄</span> Reset Form
                </button>
            </div>
        </form>
    </div>

    <!-- Users List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">👥 All Users</h3>
            <p class="card-subtitle">Manage existing user accounts</p>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>👤 Username</th>
                        <th>🔧 Role</th>
                        <th>🗑️ Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($u = $users->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $u['user_id']; ?></td>
                            <td><?php echo htmlspecialchars($u['username']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $u['role'] === 'admin' ? 'high' : 'medium'; ?>">
                                    <?php echo $u['role'] === 'admin' ? '🛡️ Admin' : '👤 User'; ?>
                                </span>
                            </td>
                            <td class="table-actions">
                                <?php if ($u['user_id'] != $_SESSION['user_id']) { ?>
                                    <a href="users.php?del=<?php echo $u['user_id']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Are you sure you want to delete user &quot;<?php echo htmlspecialchars($u['username']); ?>&quot;? This action cannot be undone.');">
                                        <span>🗑️</span> Delete
                                    </a>
                                <?php } else { ?>
                                    <span class="text-muted">Current User</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
