<?php 
require_once __DIR__ . '/config.php'; 
$page_title = "Features";
?>
<?php include BASE_PATH . '/partials/header.php'; ?>
<div class="container">
    <h1>System Features</h1>
    <p>Discover what our Hardware Repair Request Tracker can do for you.</p>

    <div class="feature-sections">
        <div class="card">
            <h3>👤 User Features</h3>
            <ul>
                <li><strong>Secure Login/Logout:</strong> Role-based access control</li>
                <li><strong>Submit Repair Requests:</strong> Easy-to-use form with all necessary fields</li>
                <li><strong>Track Your Requests:</strong> Real-time status updates and history</li>
                <li><strong>Request Details:</strong> View complete information about each request</li>
                <li><strong>Priority Setting:</strong> Set urgency level for your requests</li>
                <li><strong>Status Notifications:</strong> Visual badges for quick status recognition</li>
            </ul>
        </div>

        <div class="card">
            <h3>🔧 Admin Features</h3>
            <ul>
                <li><strong>User Management:</strong> Add, edit, delete user accounts</li>
                <li><strong>Request Management:</strong> Process and update all repair requests</li>
                <li><strong>Status Control:</strong> Change request status (Pending, In Progress, Completed, Rejected)</li>
                <li><strong>Priority Management:</strong> Adjust request priorities</li>
                <li><strong>Due Date Assignment:</strong> Set completion deadlines</li>
                <li><strong>Comprehensive Dashboard:</strong> Overview of system statistics</li>
            </ul>
        </div>

        <div class="card">
            <h3>📊 Reporting Features</h3>
            <ul>
                <li><strong>Status Reports:</strong> Breakdown by request status</li>
                <li><strong>Device Type Reports:</strong> Analysis by device categories</li>
                <li><strong>User Reports:</strong> Requests per user statistics</li>
                <li><strong>Monthly Reports:</strong> Current month activity summary</li>
                <li><strong>Overdue Tracking:</strong> Identify requests past due date</li>
                <li><strong>Recent Activity:</strong> Latest system updates</li>
            </ul>
        </div>

        <div class="card">
            <h3>💻 Technical Features</h3>
            <ul>
                <li><strong>Pure Web Technologies:</strong> HTML, CSS, JavaScript, PHP, MySQL</li>
                <li><strong>No External Dependencies:</strong> Self-contained system</li>
                <li><strong>Responsive Design:</strong> Works on desktop and mobile devices</li>
                <li><strong>Secure Sessions:</strong> Role-based access protection</li>
                <li><strong>Data Validation:</strong> Form input validation and sanitization</li>
                <li><strong>Database Integration:</strong> MySQL with proper relationships</li>
            </ul>
        </div>

        <div class="card">
            <h3>🎯 Device Support</h3>
            <ul>
                <li><strong>Computer Hardware:</strong> Laptops, desktops, workstations</li>
                <li><strong>Mobile Devices:</strong> Tablets, smartphones, e-readers</li>
                <li><strong>Peripherals:</strong> Printers, scanners, monitors</li>
                <li><strong>Network Equipment:</strong> Routers, switches, access points</li>
                <li><strong>Storage Devices:</strong> Hard drives, USB drives, external storage</li>
                <li><strong>Audio/Visual:</strong> Projectors, speakers, cameras</li>
            </ul>
        </div>

        <div class="card">
            <h3>⚡ Quick Actions</h3>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <div class="feature-actions">
                        <a href="admin/requests.php" class="btn-primary">Manage Requests</a>
                        <a href="admin/users.php" class="btn-secondary">Manage Users</a>
                        <a href="admin/reports.php" class="btn-secondary">View Reports</a>
                    </div>
                <?php else: ?>
                    <div class="feature-actions">
                        <a href="request_new.php" class="btn-primary">Submit New Request</a>
                        <a href="my_requests.php" class="btn-secondary">My Requests</a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="feature-actions">
                    <a href="auth/login.php" class="btn-primary">Login to Get Started</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
