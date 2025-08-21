<?php
require_once __DIR__ . '/../config.php';
// Restrict to admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
	header('Location: ' . url('auth/login.php'));
	exit;
}


// Enhanced dashboard statistics
$totalUsers = $mysqli->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$totalReq   = $mysqli->query("SELECT COUNT(*) c FROM requests")->fetch_assoc()['c'];
$pending    = $mysqli->query("SELECT COUNT(*) c FROM requests WHERE status='Pending'")->fetch_assoc()['c'];
$inProgress = $mysqli->query("SELECT COUNT(*) c FROM requests WHERE status='In Progress'")->fetch_assoc()['c'];
$completed  = $mysqli->query("SELECT COUNT(*) c FROM requests WHERE status='Completed'")->fetch_assoc()['c'];
$rejected   = $mysqli->query("SELECT COUNT(*) c FROM requests WHERE status='Rejected'")->fetch_assoc()['c'];

// Recent requests
$recentReq = $mysqli->query("SELECT r.*, u.username FROM requests r 
                            LEFT JOIN users u ON r.user_id = u.user_id 
                            ORDER BY r.created_at DESC LIMIT 5");

// Overdue requests (if due_date is set and past)
$overdue = $mysqli->query("SELECT COUNT(*) c FROM requests 
                          WHERE due_date < CURDATE() AND status NOT IN ('Completed', 'Rejected')")->fetch_assoc()['c'];
?>

<?php include BASE_PATH . '/partials/header.php'; ?>
<?php include BASE_PATH . '/partials/nav.php'; ?>

<div class="container">
	<h1>Admin Dashboard</h1>
	<p>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! Here's your system overview.</p>

	<h3>System Statistics</h3>
	<div class="flex">
		<div class="card">
			<h4>Total Users</h4>
			<div class="stat-number"><?php echo $totalUsers; ?></div>
			<p class="small">Registered users in system</p>
		</div>
		<div class="card">
			<h4>Total Requests</h4>
			<div class="stat-number"><?php echo $totalReq; ?></div>
			<p class="small">All time requests</p>
		</div>
		<div class="card">
			<h4>Pending</h4>
			<div class="stat-number"><?php echo $pending; ?></div>
			<p class="small">Awaiting review</p>
		</div>
		<div class="card">
			<h4>In Progress</h4>
			<div class="stat-number"><?php echo $inProgress; ?></div>
			<p class="small">Being worked on</p>
		</div>
	</div>

	<div class="flex">
		<div class="card">
			<h4>Completed</h4>
			<div class="stat-number"><?php echo $completed; ?></div>
			<p class="small">Successfully completed</p>
		</div>
		<div class="card">
			<h4>Rejected</h4>
			<div class="stat-number"><?php echo $rejected; ?></div>
			<p class="small">Could not be processed</p>
		</div>
		<div class="card">
			<h4>Overdue</h4>
			<div class="stat-number"><?php echo $overdue; ?></div>
			<p class="small">Past due date</p>
		</div>
		<div class="card">
			<h4>Quick Actions</h4>
			<div class="dashboard-actions">
				<a href="<?php echo url('admin/requests.php'); ?>" class="btn-primary">Manage Requests</a>
				<a href="<?php echo url('admin/users.php'); ?>" class="btn-secondary">Manage Users</a>
			</div>
		</div>
	</div>

	<h3 class="mt">Recent Requests</h3>
	<div class="recent-requests">
		<table>
			<tr>
				<th>ID</th>
				<th>User</th>
				<th>Device</th>
				<th>Status</th>
				<th>Created</th>
				<th>Action</th>
			</tr>
			<?php if ($recentReq->num_rows > 0): ?>
				<?php while ($row = $recentReq->fetch_assoc()): ?>
					<tr>
						<td><?php echo $row['request_id']; ?></td>
						<td><?php echo htmlspecialchars($row['username']); ?></td>
						<td><?php echo htmlspecialchars($row['device_type']); ?></td>
						<td><span class="badge badge-<?php echo strtolower(str_replace(' ', '', $row['status'])); ?>"><?php echo $row['status']; ?></span></td>
						<td><?php echo date('M j', strtotime($row['created_at'])); ?></td>
						<td><a href="<?php echo url('admin/request_view.php?id=' . $row['request_id']); ?>">View</a></td>
					</tr>
				<?php endwhile; ?>
			<?php else: ?>
				<tr>
					<td colspan="6" class="text-center">No requests found</td>
				</tr>
			<?php endif; ?>
		</table>
	</div>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
