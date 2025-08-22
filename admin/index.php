<?php
require_once __DIR__ . '/../config.php';
$page_title = "Admin Dashboard";

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

<div class="container">
	<div class="dashboard-welcome">
		<h1>Admin Dashboard</h1>
		<p>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! Here's your system overview.</p>
	</div>

	<?php if ($overdue > 0): ?>
		<div class="notification-banner">
			<div class="notification-icon">⚠️</div>
			<div class="notification-content">
				<div class="notification-title">Attention Required</div>
				<div class="notification-message"><?php echo $overdue; ?> request(s) are overdue and need immediate attention.</div>
			</div>
			<a href="admin/requests.php" class="btn-secondary">Review Now</a>
		</div>
	<?php endif; ?>

	<div class="stats-grid">
		<div class="stat-card">
			<div class="stat-number"><?php echo $totalUsers; ?></div>
			<div class="stat-label">Total Users</div>
		</div>
		<div class="stat-card">
			<div class="stat-number"><?php echo $totalReq; ?></div>
			<div class="stat-label">Total Requests</div>
		</div>
		<div class="stat-card">
			<div class="stat-number"><?php echo $pending; ?></div>
			<div class="stat-label">Pending</div>
		</div>
		<div class="stat-card">
			<div class="stat-number" style="color: var(--danger-color);"><?php echo $overdue; ?></div>
			<div class="stat-label">Overdue</div>
		</div>
	</div>

	<div class="stats-grid">
		<div class="stat-card">
			<div class="stat-number" style="color: var(--primary-color);"><?php echo $inProgress; ?></div>
			<div class="stat-label">In Progress</div>
		</div>
		<div class="stat-card">
			<div class="stat-number" style="color: var(--success-color);"><?php echo $completed; ?></div>
			<div class="stat-label">Completed</div>
		</div>
		<div class="stat-card">
			<div class="stat-number" style="color: var(--gray-500);"><?php echo $rejected; ?></div>
			<div class="stat-label">Rejected</div>
		</div>
		<div class="stat-card">
			<!-- Empty for layout -->
		</div>
	</div>

	<div class="quick-actions">
		<a href="<?php echo url('admin/requests.php'); ?>" class="action-card">
			<div class="action-icon">📋</div>
			<div class="action-title">Manage Requests</div>
			<div class="action-description">View and update all repair requests</div>
		</a>
		<a href="<?php echo url('admin/users.php'); ?>" class="action-card">
			<div class="action-icon">👥</div>
			<div class="action-title">Manage Users</div>
			<div class="action-description">Add, edit, and manage system users</div>
		</a>
		<a href="<?php echo url('admin/reports.php'); ?>" class="action-card">
			<div class="action-icon">📊</div>
			<div class="action-title">View Reports</div>
			<div class="action-description">Generate and view system reports</div>
		</a>
	</div>

	<div class="card">
		<h3>Recent Requests</h3>
		<div class="table-container">
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>User</th>
						<th>Device</th>
						<th>Status</th>
						<th>Created</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($recentReq->num_rows > 0): ?>
						<?php while ($row = $recentReq->fetch_assoc()): ?>
							<tr>
								<td><?php echo $row['request_id']; ?></td>
								<td><?php echo htmlspecialchars($row['username']); ?></td>
								<td><?php echo htmlspecialchars($row['device_type']); ?></td>
								<td><span class="badge badge-<?php echo strtolower(str_replace(' ', '', $row['status'])); ?>"><?php echo $row['status']; ?></span></td>
								<td><?php echo date('M j', strtotime($row['created_at'])); ?></td>
								<td><a href="<?php echo url('admin/request_view.php?id=' . $row['request_id']); ?>" class="btn-secondary btn-mini">View</a></td>
							</tr>
						<?php endwhile; ?>
					<?php else: ?>
						<tr>
							<td colspan="6" class="text-center">No requests found</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
