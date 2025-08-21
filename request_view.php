<?php
// Public (user) request detail page
require_once __DIR__ . '/config.php';

// Simple auth check
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . url('auth/login.php'));
    exit;
}

// Read id (cast to int for safety)
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Load only own request if normal user, otherwise (admin) any request
if ($_SESSION['role'] === 'user') {
    $stmt = $mysqli->prepare('SELECT * FROM requests WHERE request_id = ? AND user_id = ?');
    $stmt->bind_param('ii', $id, $_SESSION['user_id']);
} else {
    $stmt = $mysqli->prepare('SELECT * FROM requests WHERE request_id = ?');
    $stmt->bind_param('i', $id);
}

$stmt->execute();
$r   = $stmt->get_result();
$row = $r->fetch_assoc();
?>
<?php include BASE_PATH . '/partials/header.php'; ?>
<?php include BASE_PATH . '/partials/nav.php'; ?>

<div class="container">
    <h2>Request Details</h2>

    <?php if (!$row) { ?>
        <div class="alert">Not found</div>
    <?php } else { ?>
        <table>
            <tr>
                <th>ID</th>
                <td><?php echo $row['request_id']; ?></td>
            </tr>
            <tr>
                <th>Device</th>
                <td><?php echo htmlspecialchars($row['device_type']); ?></td>
            </tr>
            <tr>
                <th>Model</th>
                <td><?php echo htmlspecialchars($row['model']); ?></td>
            </tr>
            <tr>
                <th>Serial</th>
                <td><?php echo htmlspecialchars($row['serial_no']); ?></td>
            </tr>
            <tr>
                <th>Priority</th>
                <td><?php echo $row['priority']; ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo $row['status']; ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?php echo nl2br(htmlspecialchars($row['description'])); ?></td>
            </tr>
            <tr>
                <th>Due Date</th>
                <td><?php echo $row['due_date']; ?></td>
            </tr>
            <tr>
                <th>Created</th>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
            <tr>
                <th>Updated</th>
                <td><?php echo $row['updated_at']; ?></td>
            </tr>
        </table>
    <?php } ?>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
