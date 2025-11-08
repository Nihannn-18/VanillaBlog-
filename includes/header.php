<?php 
// Load database connection and shared functions
require_once __DIR__.'/db.php'; 
require_once __DIR__.'/utils.php'; 

?>

<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Vanilla Blog</title>
<link rel="stylesheet" href="<?php echo app_url('/assets/css/style.css'); ?>">
</head>

<body>
  <!-- Navigation Bar -->
<nav class="nav">
  <div class="brand"><a href="<?php echo app_url('/'); ?>">Vanilla<span style="color:#fff">Blog</span></a></div>
  <div>
    <?php if (!empty($_SESSION['user_id'])): ?>
      <span class="meta" style="margin-right:8px;">Hi, <?php echo e($_SESSION['username']); ?></span>
      <a class="btn secondary" href="<?php echo app_url('/posts/create.php'); ?>">New Post</a>
      <a class="btn secondary" href="<?php echo app_url('/auth/logout.php'); ?>">Logout</a>
    <?php else: ?>
      <a class="btn secondary" href="<?php echo app_url('/auth/login.php'); ?>">Login</a>
      <a class="btn" href="<?php echo app_url('/auth/register.php'); ?>">Register</a>
    <?php endif; ?>
  </div>
</nav>
<div class="container">
<?php if ($m = take_flash('flash_error')): ?>
  <div class="card" style="border-color:#743e3e;color:#ffb3b3;"><?php echo e($m); ?></div>
<?php endif; ?>
<?php if ($m = take_flash('flash_success')): ?>
  <div class="card" style="border-color:#2e6b3d;color:#b6ffce;"><?php echo e($m); ?></div>
<?php endif; ?>