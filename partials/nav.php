<?php
if (!defined('BASE_PATH')) {
    require_once __DIR__ . '/../config.php';
}
?>
<nav aria-label="Main navigation">
    <!-- Public links -->
    <a href="<?php echo url('index.php'); ?>">Home</a>
    <a href="<?php echo url('features.php'); ?>">Functionalities</a>
    <a href="<?php echo url('help.php'); ?>">Help</a>

    <!-- User role links -->
    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user') { ?>
    <a href="<?php echo url('request_new.php'); ?>">New Request</a>
    <a href="<?php echo url('my_requests.php'); ?>">My Requests</a>
    <?php } ?>

    <!-- Admin role links -->
    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') { ?>
    <a href="<?php echo url('admin/index.php'); ?>">Admin</a>
    <a href="<?php echo url('admin/requests.php'); ?>">Requests</a>
    <a href="<?php echo url('admin/users.php'); ?>">Users</a>
    <a href="<?php echo url('admin/reports.php'); ?>">Reports</a>
    <?php } ?>

    <!-- Auth links -->
    <?php if (isset($_SESSION['user_id'])) { ?>
    <a href="<?php echo url('auth/logout.php'); ?>">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
    <?php } else { ?>
    <a href="<?php echo url('auth/login.php'); ?>">Login</a>
    <?php } ?>
</nav>
