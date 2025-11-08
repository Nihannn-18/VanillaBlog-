<?php
require_once __DIR__ . '/includes/header.php';

//search posts feature
$search = trim($_GET['q'] ?? '');

if ($search) {
    $stmt = $pdo->prepare("
        SELECT b.id, b.title, b.content, b.created_at, u.username
        FROM blogPost b
        JOIN user u ON b.user_id = u.id
        WHERE b.title LIKE ? OR u.username LIKE ?
        ORDER BY b.created_at DESC
    ");
    $like = "%$search%";
    $stmt->execute([$like, $like]);
} else {
    $stmt = $pdo->query("
        SELECT b.id, b.title, b.content, b.created_at, u.username
        FROM blogPost b
        JOIN user u ON b.user_id = u.id
        ORDER BY b.created_at DESC
    ");
}

$posts = $stmt->fetchAll();
?>

<!-- HERO SECTION -->  

<section class="hero">
  <h1>Welcome to <span class="highlight">VanillaBlog</span></h1>
  <p>“Your story doesn’t need to be perfect — it just needs to be told.”</p>
  <p>Write simply, think deeply, and let your words build worlds.</p>
</section>

<!--  SEARCH BAR  -->

<form class="search-bar" method="get" action="">
  <input type="text" class="input" name="q" placeholder="Search posts..." value="<?php echo e($search); ?>">
  <button class="btn secondary">Search</button>
  <?php if ($search): ?>
    <a href="<?php echo app_url('/'); ?>" class="btn">Clear</a>
  <?php endif; ?>
</form>

<h1>Latest Posts</h1>

<?php if (!$posts): ?>
  <div class="card">No posts found. <?php if (!empty($_SESSION['user_id'])): ?><a href="<?php echo app_url('/posts/create.php'); ?>">Create one</a>.<?php endif; ?></div>
<?php endif; ?>

<?php foreach ($posts as $p): ?>
  <div class="card list-item">
    <h2><a href="<?php echo app_url('/posts/view.php?id=' . (int)$p['id']); ?>"><?php echo e($p['title']); ?></a></h2>
    <div class="meta">
      by <?php echo e($p['username']); ?> — <?php echo e($p['created_at']); ?>
    </div>
    <p>
      <?php 
        // Show short preview of content (first 150 chars)
        echo e(substr(strip_tags($p['content']), 0, 150)) . '...'; 
      ?>
    </p>
    <a class="btn secondary" href="<?php echo app_url('/posts/view.php?id=' . (int)$p['id']); ?>">Read More</a>
  </div>
<?php endforeach; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
