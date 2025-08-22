<?php
// Header partial expects config already included; fallback if accessed directly
if (!defined('BASE_PATH')) {
	require_once __DIR__ . '/../config.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Hardware Repair Request Tracker</title>
	<link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>">
	<script src="<?php echo asset('js/app.js'); ?>" defer></script>
	<!-- Simple favicon (optional) -->
	<link rel="icon" type="image/png" href="<?php echo url('assets/favicon.png'); ?>">
</head>
<body>
<!-- Site Header / Could add a logo text here if wanted later -->
