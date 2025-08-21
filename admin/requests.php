<?php
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . url('auth/login.php'));
    exit;
}


$msg = '';
if (isset($_POST['update'])) {
    $rid = (int)$_POST['request_id'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];
    $due = $_POST['due_date'] !== '' ? $_POST['due_date'] : NULL;
    
    $stmt = $mysqli->prepare('UPDATE requests SET status=?, priority=?, due_date=?, updated_at=NOW() WHERE request_id=?');
    $stmt->bind_param('sssi', $status, $priority, $due, $rid);
    if ($stmt->execute()) {
        $msg = 'Request updated successfully';
    }
}

$all = $mysqli->query('SELECT r.*, u.username FROM requests r JOIN users u ON r.user_id=u.user_id ORDER BY r.created_at DESC');
?>

<?php include BASE_PATH . '/partials/header.php'; ?>
<?php include BASE_PATH . '/partials/nav.php'; ?>

<div class="container">
    <h1>Manage All Requests</h1>
    <p>Update status, priority, and due dates for all repair requests.</p>
    
    <?php if ($msg): ?>
        <div class="success"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>
    
    <div class="requests-table">
        <table>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Device</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Due Date</th>
                <th>Quick Update</th>
                <th>View</th>
            </tr>
            <?php while ($r = $all->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $r['request_id']; ?></td>
                    <td><?php echo htmlspecialchars($r['username']); ?></td>
                    <td>
                        <?php echo htmlspecialchars($r['device_type']); ?>
                        <?php if ($r['model']): ?>
                            <br><small class="text-muted"><?php echo htmlspecialchars($r['model']); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge badge-<?php echo strtolower(str_replace(' ', '', $r['status'])); ?>">
                            <?php echo $r['status']; ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-<?php echo strtolower($r['priority']); ?>">
                            <?php echo $r['priority']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($r['due_date']): ?>
                            <?php 
                            $due = new DateTime($r['due_date']);
                            $now = new DateTime();
                            $isOverdue = $due < $now && !in_array($r['status'], ['Completed', 'Rejected']);
                            ?>
                            <span class="<?php echo $isOverdue ? 'text-danger' : ''; ?>">
                                <?php echo $due->format('M j, Y'); ?>
                                <?php if ($isOverdue): ?>(Overdue)<?php endif; ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted">Not set</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="post" class="inline-form">
                            <input type="hidden" name="request_id" value="<?php echo $r['request_id']; ?>">
                            
                            <div class="form-mini">
                                <select name="status" class="mini-select">
                                    <option value="Pending" <?php if ($r['status'] === 'Pending') echo 'selected'; ?>>Pending</option>
                                    <option value="In Progress" <?php if ($r['status'] === 'In Progress') echo 'selected'; ?>>In Progress</option>
                                    <option value="Completed" <?php if ($r['status'] === 'Completed') echo 'selected'; ?>>Completed</option>
                                    <option value="Rejected" <?php if ($r['status'] === 'Rejected') echo 'selected'; ?>>Rejected</option>
                                </select>
                                
                                <select name="priority" class="mini-select">
                                    <option value="Low" <?php if ($r['priority'] === 'Low') echo 'selected'; ?>>Low</option>
                                    <option value="Medium" <?php if ($r['priority'] === 'Medium') echo 'selected'; ?>>Medium</option>
                                    <option value="High" <?php if ($r['priority'] === 'High') echo 'selected'; ?>>High</option>
                                </select>
                                
                                <input type="date" name="due_date" value="<?php echo $r['due_date']; ?>" class="mini-input">
                                
                                <button name="update" value="1" class="btn-primary btn-mini">Save</button>
                            </div>
                        </form>
                    </td>
                    <td>
                        <a href="<?php echo url('admin/request_view.php?id=' . $r['request_id']); ?>" class="btn-secondary">View</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
