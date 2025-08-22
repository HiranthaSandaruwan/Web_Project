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

// Detect optional category column
$hasCategory = false; if($cchk=$mysqli->query("SHOW COLUMNS FROM requests LIKE 'category'")){ $hasCategory = $cchk->num_rows>0; }

// Load only own request for user role (admins should use admin/request_view.php)
$stmt = $mysqli->prepare('SELECT * FROM requests WHERE request_id = ? AND user_id = ?');
$stmt->bind_param('ii', $id, $_SESSION['user_id']);

$stmt->execute();
$r   = $stmt->get_result();
$row = $r->fetch_assoc();

// Handle adding a comment
$cmsg='';
if ($row && $_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_comment'])) {
    $comment = trim($_POST['comment_text']);
    if ($comment!=='') {
        $cstmt = $mysqli->prepare('INSERT INTO comments (request_id, user_id, comment_text, admin_only) VALUES (?,?,?,0)');
        $cstmt->bind_param('iis', $row['request_id'], $_SESSION['user_id'], $comment);
        if ($cstmt->execute()) { $cmsg='Comment added successfully'; } else { $cmsg='Error adding comment'; }
    } else { $cmsg='Enter a comment'; }
}

// Comments (public + own user comments)
if ($row) {
    $csel = $mysqli->prepare('SELECT c.comment_text, c.created_at, c.admin_only, u.username FROM comments c JOIN users u ON c.user_id=u.user_id WHERE c.request_id=? AND (c.admin_only=0 OR c.user_id=?) ORDER BY c.created_at DESC LIMIT 50');
    $csel->bind_param('ii', $row['request_id'], $_SESSION['user_id']);
    $csel->execute();
    $comments = $csel->get_result();
    $hsel = $mysqli->prepare('SELECT field_changed, old_value, new_value, changed_at FROM request_history WHERE request_id=? ORDER BY changed_at DESC LIMIT 50');
    $hsel->bind_param('i', $row['request_id']);
    $hsel->execute();
    $history = $hsel->get_result();
}
?>
<?php include BASE_PATH . '/partials/header.php'; ?>
<div class="container">
    <h2>Request Details</h2>

    <?php if (!$row) { ?>
        <div class="alert">Not found</div>
    <?php } else { ?>
        <div class="card">
            <div class="table-container">
                <table>
                    <tbody>
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
                        <?php if($hasCategory){ ?>
                        <tr>
                            <th>Category</th>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                        </tr>
                        <?php } ?>
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
                    </tbody>
                </table>
            </div>
        </div>

        <h3 class="mt">Add Comment</h3>
        <?php if ($cmsg): ?><div class="<?php echo $cmsg==='Comment added successfully'?'success':'alert'; ?>"><?php echo htmlspecialchars($cmsg); ?></div><?php endif; ?>
        <form method="post">
            <textarea name="comment_text" required placeholder="Add your comment..."></textarea>
            <button type="submit" name="add_comment" value="1">Post Comment</button>
        </form>

        <h3 class="mt">Comments</h3>
        <div class="comments-list">
            <?php if(isset($comments) && $comments->num_rows): while($c=$comments->fetch_assoc()): ?>
                <div class="comment-item">
                    <div class="small"><strong><?php echo htmlspecialchars($c['username']); ?></strong> - <?php echo $c['created_at']; ?><?php if($c['admin_only']) echo ' (admin)'; ?></div>
                    <div><?php echo nl2br(htmlspecialchars($c['comment_text'])); ?></div>
                </div>
            <?php endwhile; else: ?>
                <p class="small">No comments yet.</p>
            <?php endif; ?>
        </div>

        <h3 class="mt">History</h3>
        <ul class="history-list">
            <?php if(isset($history) && $history->num_rows): while($h=$history->fetch_assoc()): ?>
                <li class="small"><?php echo $h['changed_at']; ?> - <?php echo htmlspecialchars($h['field_changed']); ?> changed from <strong><?php echo htmlspecialchars($h['old_value']); ?></strong> to <strong><?php echo htmlspecialchars($h['new_value']); ?></strong></li>
            <?php endwhile; else: ?>
                <li class="small">No changes yet.</li>
            <?php endif; ?>
        </ul>
    <?php } ?>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
