<?php
// Admin request detail page
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
	header('Location: ' . url('auth/login.php'));
	exit;
}


// Get request ID (simple cast to int)
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Detect optional category column
$hasCategory = false; if($cchk=$mysqli->query("SHOW COLUMNS FROM requests LIKE 'category'")){ $hasCategory = $cchk->num_rows>0; }

// Handle updates
$msg='';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['update_request'])) {
	$status = $_POST['status'];
	$priority = $_POST['priority'];
	$due = $_POST['due_date'] !== '' ? $_POST['due_date'] : NULL;
	$note = trim($_POST['note']);
	$adminOnly = isset($_POST['admin_only']) ? 1 : 0;
	$curr = $mysqli->query('SELECT status, priority, due_date FROM requests WHERE request_id='.(int)$id)->fetch_assoc();
	$u=$mysqli->prepare('UPDATE requests SET status=?, priority=?, due_date=?, updated_at=NOW() WHERE request_id=?');
	$u->bind_param('sssi',$status,$priority,$due,$id);
	if($u->execute()){
		$msg='Updated';
		$chg=['status'=>[$curr['status'],$status],'priority'=>[$curr['priority'],$priority],'due_date'=>[$curr['due_date'],$due]];
		foreach($chg as $f=>$v){ if(($v[0]??'')!=($v[1]??'')){ $h=$mysqli->prepare('INSERT INTO request_history (request_id, field_changed, old_value, new_value) VALUES (?,?,?,?)'); $h->bind_param('isss',$id,$f,$v[0],$v[1]); $h->execute(); }}
		if($note!==''){ $c=$mysqli->prepare('INSERT INTO comments (request_id,user_id,comment_text,admin_only) VALUES (?,?,?,?)'); $c->bind_param('iisi',$id,$_SESSION['user_id'],$note,$adminOnly); $c->execute(); }
	}
}

// Load request with user who created it (includes category now)
$stmt = $mysqli->prepare('SELECT r.*, u.username FROM requests r JOIN users u ON r.user_id = u.user_id WHERE r.request_id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
if ($row){
	$comments = $mysqli->query('SELECT c.comment_text,c.created_at,c.admin_only,u.username FROM comments c JOIN users u ON c.user_id=u.user_id WHERE c.request_id='.(int)$id.' ORDER BY c.created_at DESC LIMIT 50');
	$history = $mysqli->query('SELECT field_changed,old_value,new_value,changed_at FROM request_history WHERE request_id='.(int)$id.' ORDER BY changed_at DESC LIMIT 50');
}
?>
<?php include BASE_PATH . '/partials/header.php'; ?>
<?php include BASE_PATH . '/partials/nav.php'; ?>

<div class="container">
	<h2>Request Detail (Admin)</h2>
	<?php if($msg) echo '<div class="success">'.htmlspecialchars($msg).'</div>'; ?>

	<?php if (!$row) { ?>
		<div class="alert">Not found</div>
	<?php } else { ?>
		<table>
			<tr>
				<th>ID</th>
				<td><?php echo $row['request_id']; ?></td>
			</tr>
			<tr>
				<th>User</th>
				<td><?php echo htmlspecialchars($row['username']); ?></td>
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
			<?php if($hasCategory){ ?>
			<tr>
				<th>Category</th>
				<td><?php echo htmlspecialchars($row['category']); ?></td>
			</tr>
			<?php } ?>
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

		<h3 class="mt">Update Request</h3>
		<form method="post">
			<input type="hidden" name="update_request" value="1">
			<label>Status</label>
			<select name="status"><?php foreach(['Pending','In Progress','Completed','Rejected'] as $s){ $sel=$row['status']===$s?'selected':''; echo '<option '.$sel.'>'.$s.'</option>'; } ?></select>
			<label>Priority</label>
			<select name="priority"><?php foreach(['Low','Medium','High'] as $p){ $sel=$row['priority']===$p?'selected':''; echo '<option '.$sel.'>'.$p.'</option>'; } ?></select>
			<label>Due Date</label>
			<input type="date" name="due_date" value="<?php echo htmlspecialchars($row['due_date']); ?>">
			<label>Note (optional)</label>
			<textarea name="note" placeholder="Add admin note..."></textarea>
			<label><input type="checkbox" name="admin_only" value="1"> Admin Only</label>
			<div><button class="btn-primary" type="submit">Save</button></div>
		</form>

		<h3 class="mt">Comments</h3>
		<div class="comments-list">
			<?php if(isset($comments)&&$comments->num_rows): while($c=$comments->fetch_assoc()): ?>
				<div class="comment-item"><div class="small"><strong><?php echo htmlspecialchars($c['username']); ?></strong> - <?php echo $c['created_at']; ?><?php if($c['admin_only']) echo ' (admin only)'; ?></div><div><?php echo nl2br(htmlspecialchars($c['comment_text'])); ?></div></div>
			<?php endwhile; else: ?><p class="small">No comments.</p><?php endif; ?>
		</div>

		<h3 class="mt">History</h3>
		<ul class="history-list">
			<?php if(isset($history)&&$history->num_rows): while($h=$history->fetch_assoc()): ?>
				<li class="small"><?php echo $h['changed_at']; ?> - <?php echo htmlspecialchars($h['field_changed']); ?> changed from <strong><?php echo htmlspecialchars($h['old_value']); ?></strong> to <strong><?php echo htmlspecialchars($h['new_value']); ?></strong></li>
			<?php endwhile; else: ?><li class="small">No changes yet.</li><?php endif; ?>
		</ul>
	<?php } ?>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
