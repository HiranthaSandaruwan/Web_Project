<?php
// Admin request detail page
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
	header('Location: ' . url('auth/login.php'));
	exit;
}


// Get request ID (simple cast to int)
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Load request with user who created it
$stmt = $mysqli->prepare('SELECT r.*, u.username FROM requests r JOIN users u ON r.user_id = u.user_id WHERE r.request_id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
?>
<?php include BASE_PATH . '/partials/header.php'; ?>
<?php include BASE_PATH . '/partials/nav.php'; ?>

<div class="container">
	<h2>Request Detail (Admin)</h2>

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
