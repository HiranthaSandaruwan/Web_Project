<?php
// Minimal header for auth pages (no sidebar layout)
if (!defined('BASE_PATH')) { require_once __DIR__ . '/../config.php'; }
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Login - Hardware Tracker</title>
<link rel="stylesheet" href="<?php echo asset('css/unified-styles.css'); ?>">
<link rel="icon" type="image/png" href="<?php echo url('assets/favicon.png'); ?>">
</head>
<body class="auth-page">
