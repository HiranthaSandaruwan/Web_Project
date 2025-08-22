<?php
if (!defined('BASE_PATH')) {
    require_once __DIR__ . '/../config.php';
}
?>
<nav aria-label="Main navigation">
    <div class="nav-container">
        <a href="<?php echo url('index.php'); ?>" class="nav-brand">Hardware Repair Tracker</a>
        
        <div class="nav-links">
            <a href="<?php echo url('index.php'); ?>">Home</a>
            
            <?php if(isset($_SESSION['user_id']) && $_SESSION['role']==='user'): ?>
                <a href="<?php echo url('request_new.php'); ?>">Submit Request</a>
                <a href="<?php echo url('my_requests.php'); ?>">My Requests</a>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['user_id']) && $_SESSION['role']==='admin'): ?>
                <a href="<?php echo url('admin/index.php'); ?>">Admin Dashboard</a>
                <a href="<?php echo url('admin/requests.php'); ?>">Manage Requests</a>
                <a href="<?php echo url('admin/users.php'); ?>">Manage Users</a>
                <a href="<?php echo url('admin/reports.php'); ?>">Reports</a>
            <?php endif; ?>
            
            <a href="<?php echo url('help.php'); ?>">Help</a>
        </div>
        
        <div class="nav-links">
            <?php if(isset($_SESSION['user_id'])): ?>
                <span class="nav-user">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="<?php echo url('auth/logout.php'); ?>">Logout</a>
            <?php else: ?>
                <a href="<?php echo url('auth/login.php'); ?>">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
