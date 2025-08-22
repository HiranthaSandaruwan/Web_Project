<?php require_once __DIR__ . '/config.php'; ?>
<?php include BASE_PATH . '/partials/header.php'; ?>
<?php include BASE_PATH . '/partials/nav.php'; ?>

<div class="container">
    <h1>Hardware Repair Request Tracker</h1>
    
    <div class="welcome-section">
        <h2>Welcome to Our Repair Service</h2>
        <p>Our hardware repair tracking system helps students and staff submit repair requests and track their progress efficiently.</p>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <div class="card mt">
                    <h3>Admin Quick Actions</h3>
                    <div class="flex">
                        <a href="Admin/requests.php" class="btn-secondary">View All Requests</a>
                        <a href="Admin/users.php" class="btn-secondary">Manage Users</a>
                        <a href="Admin/reports.php" class="btn-secondary">View Reports</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="card mt">
                    <h3>User Quick Actions</h3>
                    <div class="flex">
                        <a href="request_new.php" class="btn-primary">Submit New Request</a>
                        <a href="my_requests.php" class="btn-secondary">View My Requests</a>
                        <a href="help.php" class="btn-secondary">Get Help</a>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="card mt">
                <h3>Get Started</h3>
                <p>Please login to submit repair requests or manage the system.</p>
                <a href="auth/login.php" class="btn-primary">Login</a>
            </div>
        <?php endif; ?>
        
        <div class="info-cards mt">
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
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
