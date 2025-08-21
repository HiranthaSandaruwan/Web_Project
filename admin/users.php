<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require_once '../db.php';

$msg = '';

// Add user
if (isset($_POST['add'])) {
    $u = trim($_POST['username']);
    $p = trim($_POST['password']);
    $r = $_POST['role'];
    if ($u !== '' && $p !== '') {
        $stmt = $mysqli->prepare('INSERT INTO users (username, password, role) VALUES (?,?,?)');
        $stmt->bind_param('sss', $u, $p, $r);
        if ($stmt->execute()) {
            $msg = 'User added';
        } else {
            $msg = 'Error adding';
        }
    } else {
        $msg = 'Fill fields';
    }
}

// Delete user (not self)
if (isset($_GET['del'])) {
    $delId = (int) $_GET['del'];
    if ($delId !== $_SESSION['user_id']) {
        $mysqli->query('DELETE FROM users WHERE user_id=' . $delId);
    }
}

// Edit role
if (isset($_POST['edit'])) {
    $eid  = (int) $_POST['user_id'];
    $role = $_POST['role'];
    $stmt = $mysqli->prepare('UPDATE users SET role=? WHERE user_id=?');
    $stmt->bind_param('si', $role, $eid);
    $stmt->execute();
}

$users = $mysqli->query('SELECT * FROM users ORDER BY user_id');
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/nav.php'; ?>

<div class="container">
    <h2>Manage Users</h2>
    <?php if ($msg) { echo '<div class="alert">' . $msg . '</div>'; } ?>

    <h3>Add User</h3>
    <form method="post">
        <input type="hidden" name="add" value="1">
        <label>Username</label>
        <input name="username" required>

        <label>Password</label>
        <input name="password" required>

        <label>Role</label>
        <select name="role">
            <option value="user">user</option>
            <option value="admin">admin</option>
        </select>

        <button type="submit">Add</button>
    </form>

    <h3>All Users</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Change Role</th>
            <th>Delete</th>
        </tr>
        <?php while ($u = $users->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $u['user_id']; ?></td>
                <td><?php echo htmlspecialchars($u['username']); ?></td>
                <td><?php echo $u['role']; ?></td>
                <td>
                    <form method="post" style="display:inline-block;">
                        <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
                        <select name="role">
                            <option <?php if ($u['role'] === 'user') echo 'selected'; ?>>user</option>
                            <option <?php if ($u['role'] === 'admin') echo 'selected'; ?>>admin</option>
                        </select>
                        <button name="edit" value="1">Save</button>
                    </form>
                </td>
                <td>
                    <?php if ($u['user_id'] != $_SESSION['user_id']) { ?>
                        <a onclick="return confirmDelete();" href="users.php?del=<?php echo $u['user_id']; } ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include '../partials/footer.php'; ?>
