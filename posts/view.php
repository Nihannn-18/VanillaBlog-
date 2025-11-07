<?php
require_once __DIR__.'/../includes/header.php';
require_once __DIR__.'/../includes/markdown.php';
require_once __DIR__.'/../includes/auth.php';

// Get post ID from URL and fetch post with author details
$id=(int)($_GET['id']??0);
$stmt=$pdo->prepare('SELECT b.*,u.username FROM blogPost b JOIN user u ON b.user_id=u.id WHERE b.id=?');
$stmt->execute([$id]);
$post=$stmt->fetch();

// Handle case where post doesn't exist
if(!$post){
    http_response_code(404);
    echo '<div class="card">Post not found.</div>';
    require_once __DIR__.'/../includes/footer.php';
    exit;
}

// Determine if current user can edit or delete (admin or owner)
$canEdit = current_user() && (is_admin() || $_SESSION['user_id']==$post['user_id']);
?>
<div class="card">
  <h1><?php echo e($post['title']); ?></h1>
  <div class="meta">by <?php echo e($post['username']); ?> • <?php echo e($post['created_at']); ?><?php if($post['updated_at']) echo ' • updated '.e($post['updated_at']); ?></div>
  <hr>
  <div><?php echo md_to_html($post['content']); ?></div>
</div>
<?php if($canEdit): ?>
  <div class="card">
    <a class="btn secondary" href="<?php echo app_url('/posts/edit.php?id='.(int)$post['id']); ?>">Edit</a>
    <form method="post" action="<?php echo app_url('/posts/delete.php'); ?>" style="display:inline" onsubmit="return confirm('Delete this post?');">
      <input type="hidden" name="id" value="<?php echo (int)$post['id']; ?>">
      <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
      <button class="btn danger" type="submit">Delete</button>
    </form>
  </div>
<?php endif; ?>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
