<?php require_once __DIR__ . '/config.php'; ?>
<?php include BASE_PATH . '/partials/header.php'; ?>
<?php include BASE_PATH . '/partials/nav.php'; ?>
<?php
$notifCount = 0;
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
}
?>

<div class="container">
    <div class="welcome-section">
        <h1>Hardware Repair Request Tracker</h1>
        <p>Our hardware repair tracking system helps students and staff submit repair requests and track their progress efficiently.</p>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <div class="card">
                    <h3>Admin Dashboard</h3>
                    <div class="flex">
                        <a href="admin/requests.php" class="btn-primary">View All Requests</a>
                        <a href="admin/users.php" class="btn-secondary">Manage Users</a>
                        <a href="admin/reports.php" class="btn-secondary">View Reports</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <h3>Quick Actions</h3>
                    <div class="flex">
                        <a href="request_new.php" class="btn-primary">Submit New Request</a>
                        <a href="my_requests.php" class="btn-secondary">View My Requests</a>
                    </div>
                    <?php if ($notifCount>0): ?>
                        <div class="alert mt">
                            <strong><?php echo $notifCount; ?></strong> request(s) have been updated since your last login. 
                            <a href="my_requests.php" class="btn-secondary btn-mini">View Updates</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="card text-center">
                <h3>Get Started</h3>
                <p>Please login to submit repair requests or manage the system.</p>
                <a href="auth/login.php" class="btn-primary">Login to Continue</a>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="info-cards">
        <div class="card">
            <h3>For Students & Staff</h3>
            <ul>
                <li>Submit repair requests online</li>
                <li>Track request status in real-time</li>
                <li>View repair history</li>
                <li>Get email notifications</li>
            </ul>
        </div>
        
        <div class="card">
            <h3>Supported Devices</h3>
            <ul>
                <li>Laptops & Desktops</li>
                <li>Tablets & Smartphones</li>
                <li>Printers & Scanners</li>
                <li>Networking Equipment</li>
            </ul>
        </div>
        
        <div class="card">
            <h3>Need Help?</h3>
            <p>Visit our <a href="help.php">Help Center</a> for guides on how to use the system or contact support.</p>
            <a href="help.php" class="btn-secondary">Get Help</a>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
