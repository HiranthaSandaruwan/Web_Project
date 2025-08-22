<?php
// Header partial expects config already included; fallback if accessed directly
if (!defined('BASE_PATH')) {
	require_once __DIR__ . '/../config.php';
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>🔧 Hardware Request Manager</title>
	<link rel="stylesheet" href="<?php echo asset('css/unified-styles.css'); ?>">
	<script src="<?php echo asset('js/app.js'); ?>" defer></script>
	<!-- Simple favicon (optional) -->
	<link rel="icon" type="image/png" href="<?php echo url('assets/favicon.png'); ?>">
</head>
<body>
<div class="app-layout">
	<!-- Sidebar Navigation -->
	<nav id="sidebar" class="sidebar">
		<div class="sidebar-header">
			<div class="sidebar-logo">
				<span class="emoji">🔧</span>
				<span class="text">HW Manager</span>
			</div>
			<button id="sidebar-toggle" class="sidebar-toggle" title="Toggle Sidebar">
				<span>≡</span>
			</button>
		</div>
		
		<div class="sidebar-nav">
			<?php if (isset($_SESSION['user_id'])): ?>
				<!-- Main Navigation -->
				<div class="nav-section">
					<div class="nav-section-title">Main</div>
					<a href="<?php echo url('index.php'); ?>" class="nav-link">
						<span class="icon">🏠</span>
						<span class="text">Dashboard</span>
					</a>
					<a href="<?php echo url('request_new.php'); ?>" class="nav-link">
						<span class="icon">➕</span>
						<span class="text">New Request</span>
					</a>
					<a href="<?php echo url('my_requests.php'); ?>" class="nav-link">
						<span class="icon">📋</span>
						<span class="text">My Requests</span>
					</a>
				</div>

				<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
				<!-- Admin Section -->
				<div class="nav-section">
					<div class="nav-section-title">Administration</div>
					<a href="<?php echo url('admin/index.php'); ?>" class="nav-link">
						<span class="icon">📊</span>
						<span class="text">Admin Dashboard</span>
					</a>
					<a href="<?php echo url('admin/requests.php'); ?>" class="nav-link">
						<span class="icon">🔍</span>
						<span class="text">All Requests</span>
					</a>
					<a href="<?php echo url('admin/users.php'); ?>" class="nav-link">
						<span class="icon">👥</span>
						<span class="text">User Management</span>
					</a>
					<a href="<?php echo url('admin/reports.php'); ?>" class="nav-link">
						<span class="icon">📈</span>
						<span class="text">Reports</span>
					</a>
				</div>
				<?php endif; ?>

				<!-- Support Section -->
				<div class="nav-section">
					<div class="nav-section-title">Support</div>
					<a href="<?php echo url('features.php'); ?>" class="nav-link">
						<span class="icon">⚡</span>
						<span class="text">Features</span>
					</a>
					<a href="<?php echo url('help.php'); ?>" class="nav-link">
						<span class="icon">❓</span>
						<span class="text">Help</span>
					</a>
				</div>
			<?php else: ?>
				<!-- Guest Navigation -->
				<div class="nav-section">
					<div class="nav-section-title">Access</div>
					<a href="<?php echo url('auth/login.php'); ?>" class="nav-link">
						<span class="icon">🔐</span>
						<span class="text">Login</span>
					</a>
					<a href="<?php echo url('help.php'); ?>" class="nav-link">
						<span class="icon">❓</span>
						<span class="text">Help</span>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</nav>

	<!-- Main Content Area -->
	<main class="main-content">
		<!-- Top Header -->
		<header class="top-header">
			<div class="header-left">
				<button id="mobile-menu-toggle" class="sidebar-toggle" title="Toggle Menu" style="display: none;">
					<span>≡</span>
				</button>
				<h1 class="page-title"><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Hardware Requests'; ?></h1>
			</div>
			<div class="header-right">
				<button id="theme-toggle" class="theme-toggle" title="Toggle Dark/Light Mode">
					<span>🌙</span> Dark
				</button>
				<?php if (isset($_SESSION['user_id'])): ?>
				<div class="user-info">
					<div class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?></div>
					<div>
						<div><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></div>
						<small><?php echo ucfirst($_SESSION['role'] ?? 'user'); ?></small>
					</div>
					<a href="<?php echo url('auth/logout.php'); ?>" class="btn btn-sm" style="margin-left: 12px;">Logout</a>
				</div>
				<?php endif; ?>
			</div>
		</header>

		<!-- Page Content -->
		<div class="page-content">
