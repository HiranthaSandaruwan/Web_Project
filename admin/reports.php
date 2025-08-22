<?php
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
	header('Location: ' . url('auth/login.php'));
	exit;
}


// Simple queries for report tables
$statusCounts = $mysqli->query("SELECT status, COUNT(*) c FROM requests GROUP BY status");
$typeCounts   = $mysqli->query("SELECT device_type, COUNT(*) c FROM requests GROUP BY device_type");
// Detect category column
$hasCategory = false;
if ($cchk = $mysqli->query("SHOW COLUMNS FROM requests LIKE 'category'")) { $hasCategory = $cchk->num_rows>0; }
$categoryCounts = $hasCategory ? $mysqli->query("SELECT category, COUNT(*) c FROM requests GROUP BY category") : null;
$userCounts   = $mysqli->query("SELECT u.username, COUNT(r.request_id) c FROM users u LEFT JOIN requests r ON u.user_id = r.user_id GROUP BY u.user_id");
$monthCounts  = $mysqli->query("SELECT DATE_FORMAT(created_at,'%Y-%m') m, COUNT(*) c FROM requests GROUP BY m ORDER BY m DESC LIMIT 6");
$overdue      = $mysqli->query("SELECT request_id, device_type, due_date FROM requests WHERE due_date IS NOT NULL AND status!='Completed' AND due_date < CURDATE()");
?>

<?php include BASE_PATH . '/partials/header.php'; ?>
<?php include BASE_PATH . '/partials/nav.php'; ?>

<div class="container">
	<h2>Reports</h2>

	<div class="grid grid-2">
		<!-- Requests by Status -->
		<div class="card">
			<h3>Requests by Status</h3>
			<div class="table-container">
				<table>
					<thead>
						<tr>
							<th>Status</th>
							<th>Count</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($r = $statusCounts->fetch_assoc()) { ?>
							<tr>
								<td><?php echo $r['status']; ?></td>
								<td><?php echo $r['c']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>

		<!-- Requests by Device Type -->
		<div class="card">
			<h3>Requests by Device Type</h3>
			<div class="table-container">
				<table>
					<thead>
						<tr>
							<th>Device Type</th>
							<th>Count</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($r = $typeCounts->fetch_assoc()) { ?>
							<tr>
								<td><?php echo htmlspecialchars($r['device_type']); ?></td>
								<td><?php echo $r['c']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="grid grid-2">
		<?php if ($hasCategory) { ?>
		<!-- Requests by Category -->
		<div class="card">
			<h3>Requests by Category</h3>
			<div class="table-container">
				<table>
					<thead>
						<tr><th>Category</th><th>Count</th></tr>
					</thead>
					<tbody>
						<?php while($c=$categoryCounts->fetch_assoc()): ?>
						<tr><td><?php echo htmlspecialchars($c['category']); ?></td><td><?php echo $c['c']; ?></td></tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php } ?>

		<!-- Requests per User -->
		<div class="card">
			<h3>Requests per User</h3>
			<div class="table-container">
				<table>
					<thead>
						<tr>
							<th>User</th>
							<th>Count</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($r = $userCounts->fetch_assoc()) { ?>
							<tr>
								<td><?php echo htmlspecialchars($r['username']); ?></td>
								<td><?php echo $r['c']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="grid grid-2">
		<!-- Requests Created (Last Months) -->
		<div class="card">
			<h3>Requests Created (Last Months)</h3>
			<div class="table-container">
				<table>
					<thead>
						<tr>
							<th>Month</th>
							<th>Count</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($r = $monthCounts->fetch_assoc()) { ?>
							<tr>
								<td><?php echo $r['m']; ?></td>
								<td><?php echo $r['c']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>

		<!-- Overdue Requests -->
		<div class="card">
			<h3>Overdue Requests</h3>
			<div class="table-container">
				<table>
					<thead>
						<tr>
							<th>ID</th>
							<th>Device</th>
							<th>Due Date</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($r = $overdue->fetch_assoc()) { ?>
							<tr>
								<td><?php echo $r['request_id']; ?></td>
								<td><?php echo htmlspecialchars($r['device_type']); ?></td>
								<td><?php echo $r['due_date']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
