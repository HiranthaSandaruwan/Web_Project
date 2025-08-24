<?php 
require_once __DIR__ . '/config.php'; 
$page_title = "Dashboard";
?>
<?php include BASE_PATH . '/partials/header.php'; ?>
<?php
$notifCount = 0;
$pendingCount = 0;
$totalRequests = 0;

if (isset($_SESSION['user_id']) && $_SESSION['role']==='user') {
    // Check columns exist (in case migration not applied yet)
    $hasUpdated = false; $hasLastLogin=false;
    if ($c1=$mysqli->query("SHOW COLUMNS FROM requests LIKE 'updated_at'")) { $hasUpdated = $c1->num_rows>0; }
    if ($c2=$mysqli->query("SHOW COLUMNS FROM users LIKE 'last_login'")) { $hasLastLogin = $c2->num_rows>0; }
    if ($hasUpdated && $hasLastLogin) {
        $prev = $_SESSION['prev_login'] ?? null;
        if ($prev) {
            $stmtN = $mysqli->prepare("SELECT COUNT(*) c FROM requests WHERE user_id=? AND updated_at IS NOT NULL AND updated_at > ?");
            $stmtN->bind_param('is', $_SESSION['user_id'], $prev);
            if($stmtN->execute()) {
                $notifCount = $stmtN->get_result()->fetch_assoc()['c'];
            }
        }
    }
    
    // Get user's request counts
    $uid = (int)$_SESSION['user_id'];
    $pendingQuery = $mysqli->query("SELECT COUNT(*) as count FROM requests WHERE user_id = $uid AND status = 'Pending'");
    $pendingCount = $pendingQuery->fetch_assoc()['count'];
    
    $totalQuery = $mysqli->query("SELECT COUNT(*) as count FROM requests WHERE user_id = $uid");
    $totalRequests = $totalQuery->fetch_assoc()['count'];
}

// Admin stats
$adminStats = [];
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
    $pendingAdmin = $mysqli->query("SELECT COUNT(*) as count FROM requests WHERE status = 'Pending'")->fetch_assoc()['count'];
    $inProgress = $mysqli->query("SELECT COUNT(*) as count FROM requests WHERE status = 'In Progress'")->fetch_assoc()['count'];
    $totalAdmin = $mysqli->query("SELECT COUNT(*) as count FROM requests")->fetch_assoc()['count'];
    $overdue = $mysqli->query("SELECT COUNT(*) as count FROM requests WHERE due_date IS NOT NULL AND status != 'Completed' AND due_date < CURDATE()")->fetch_assoc()['count'];
    
    $adminStats = [
        'pending' => $pendingAdmin,
        'inProgress' => $inProgress,
        'total' => $totalAdmin,
        'overdue' => $overdue
    ];
}
?>

<div class="container">
        <?php // Auth guard in config ensures user is logged in. Show role-specific dashboard only. ?>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <!-- Admin Dashboard -->
            <div class="dashboard-welcome">
                <h1>👋 Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p>Admin Dashboard - Hardware Repair Request Management System</p>
            </div>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $adminStats['pending']; ?></div>
                    <div class="stat-label">⏳ Pending Requests</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $adminStats['inProgress']; ?></div>
                    <div class="stat-label">🔧 In Progress</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $adminStats['total']; ?></div>
                    <div class="stat-label">📊 Total Requests</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number text-danger"><?php echo $adminStats['overdue']; ?></div>
                    <div class="stat-label">⚠️ Overdue</div>
                </div>
            </div>
            
            <div class="quick-actions">
                <a href="admin/requests.php" class="action-card">
                    <div class="action-icon">📋</div>
                    <div class="action-title">Manage Requests</div>
                    <div class="action-description">View and update all repair requests</div>
                </a>
                <a href="admin/users.php" class="action-card">
                    <div class="action-icon">👥</div>
                    <div class="action-title">Manage Users</div>
                    <div class="action-description">Add, edit, and manage system users</div>
                </a>
                <a href="admin/reports.php" class="action-card">
                    <div class="action-icon">📊</div>
                    <div class="action-title">View Reports</div>
                    <div class="action-description">Generate and view system reports</div>
                </a>
            </div>
            
    <?php else: ?>
            <!-- User Dashboard -->
            <div class="dashboard-welcome">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p>Track your repair requests and stay updated on their progress</p>
            </div>
            
            <?php if ($notifCount > 0): ?>
                <div class="notification-banner">
                    <div class="notification-icon">🔔</div>
                    <div class="notification-content">
                        <div class="notification-title">You have updates!</div>
                        <div class="notification-message"><?php echo $notifCount; ?> request(s) have been updated since your last login.</div>
                    </div>
                    <a href="my_requests.php" class="btn-secondary">View Updates</a>
                </div>
            <?php endif; ?>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $pendingCount; ?></div>
                    <div class="stat-label">Pending Requests</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $totalRequests; ?></div>
                    <div class="stat-label">Total Requests</div>
                </div>
            </div>
            
            <div class="quick-actions">
                <a href="request_new.php" class="action-card">
                    <div class="action-icon">➕</div>
                    <div class="action-title">Submit New Request</div>
                    <div class="action-description">Create a new repair request</div>
                </a>
                <a href="my_requests.php" class="action-card">
                    <div class="action-icon">📋</div>
                    <div class="action-title">My Requests</div>
                    <div class="action-description">View and track your requests</div>
                </a>
                <a href="help.php" class="action-card">
                    <div class="action-icon">❓</div>
                    <div class="action-title">Get Help</div>
                    <div class="action-description">Find answers and support</div>
                </a>
            </div>
    <?php endif; ?>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
