<?php
// List only the logged in user's requests
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
	header('Location: ' . url('auth/login.php'));
	exit;
}

$uid = $_SESSION['user_id'];
$res = $mysqli->query("SELECT * FROM requests WHERE user_id = " . $uid . " ORDER BY created_at DESC");
?>
<?php include BASE_PATH . '/partials/header.php'; ?>
<?php include BASE_PATH . '/partials/nav.php'; ?>

<div class="container">
	<h2>My Requests</h2>
	<table>
		<tr>
			<th>ID</th>
			<th>Device</th>
			<th>Model</th>
			<th>Status</th>
			<th>Priority</th>
			<th>Created</th>
			<th>View</th>
		</tr>
		<?php while ($row = $res->fetch_assoc()) { ?>
			<tr>
				<td><?php echo $row['request_id']; ?></td>
				<td><?php echo htmlspecialchars($row['device_type']); ?></td>
				<td><?php echo htmlspecialchars($row['model']); ?></td>
				<td><span class="badge badge-<?php echo strtolower(str_replace(' ', '', $row['status'])); ?>"><?php echo $row['status']; ?></span></td>
				<td><span class="badge badge-<?php echo strtolower($row['priority']); ?>"><?php echo $row['priority']; ?></span></td>
				<td><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
				<td><a href="<?php echo url('request_view.php?id=' . $row['request_id']); ?>" class="btn-secondary">View</a></td>
			</tr>
		<?php } ?>
	</table>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
