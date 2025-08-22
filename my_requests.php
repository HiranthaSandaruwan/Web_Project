<?php
// List only the logged in user's requests
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
	header('Location: ' . url('auth/login.php'));
	exit;
}

$uid = (int)$_SESSION['user_id'];
$prev = $_SESSION['prev_login'] ?? null;
$condPrev = $prev ? " , (updated_at IS NOT NULL AND updated_at > '".$mysqli->real_escape_string($prev)."') AS is_updated" : '';
// Detect category column
$hasCategory = false; if($cchk=$mysqli->query("SHOW COLUMNS FROM requests LIKE 'category'")){ $hasCategory = $cchk->num_rows>0; }
$res = $mysqli->query("SELECT * $condPrev FROM requests WHERE user_id = $uid ORDER BY created_at DESC");
?>
<?php include BASE_PATH . '/partials/header.php'; ?>
<?php include BASE_PATH . '/partials/nav.php'; ?>

<div class="container">
	<h2>My Requests</h2>
	<div class="table-container">
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Device</th>
					<th>Model</th>
					<?php if($hasCategory): ?><th>Category</th><?php endif; ?>
					<th>Status</th>
					<th>Priority</th>
					<th>Created</th>
					<th>View</th>
				</tr>
			</thead>
			<tbody>
				<?php while ($row = $res->fetch_assoc()) { ?>
					<tr class="<?php echo !empty($row['is_updated'])?'row-updated':''; ?>">
						<td><?php echo $row['request_id']; ?></td>
						<td><?php echo htmlspecialchars($row['device_type']); ?></td>
						<td><?php echo htmlspecialchars($row['model']); ?></td>
						<?php if($hasCategory): ?><td><?php echo htmlspecialchars($row['category']); ?></td><?php endif; ?>
						<td><span class="badge badge-<?php echo strtolower(str_replace(' ', '', $row['status'])); ?>"><?php echo $row['status']; ?></span></td>
						<td><span class="badge badge-<?php echo strtolower($row['priority']); ?>"><?php echo $row['priority']; ?></span></td>
						<td><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
						<td><a href="<?php echo url('request_view.php?id=' . $row['request_id']); ?>" class="btn-secondary btn-mini">View</a></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
