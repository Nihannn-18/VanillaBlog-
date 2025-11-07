<?php
require_once __DIR__.'/../includes/header.php';
require_once __DIR__.'/../includes/auth.php';
require_once __DIR__.'/../includes/csrf.php';

require_login();  // Allow only logged-in users to access this page

$id=(int)($_GET['id']??0);
$stmt=$pdo->prepare('SELECT * FROM blogPost WHERE id=?');
$stmt->execute([$id]);
$post=$stmt->fetch();

// If post not found, show error message
if(!$post){ http_response_code(404); echo '<div class="card">Post not found.</div>'; require_once __DIR__.'/../includes/footer.php'; exit; }

// Restrict edit access to admin or post owner only
if(!is_admin() && $_SESSION['user_id']!=$post['user_id']){
    http_response_code(403);
    echo '<div class="card">You are not authorized to edit this post.</div>';
    require_once __DIR__.'/../includes/footer.php';
    exit;
}

$errors=[]; $title=$post['title']; $content=$post['content'];

if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $title=trim($_POST['title']??'');
    $content=trim($_POST['content']??'');
    if(!$title||!$content) $errors[]='Title and content are required.';

    // If no errors, update post in the database
    if(!$errors){
        try{
            $stmt=$pdo->prepare('UPDATE blogPost SET title=?, content=?, updated_at=NOW() WHERE id=?');
            $stmt->execute([$title,$content,$id]);
            flash_success('Post updated.');
            redirect('/posts/view.php?id='.$id);
        } catch (Throwable $e){
            $errors[]='Failed to update post.';
        }
    }
}
?>
<div class="card">
  <h1>Edit Post</h1>
  <?php foreach($errors as $e) echo '<div class="meta" style="color:#ffb3b3">'.e($e).'</div>'; ?>
  <form method="post">
    <?php echo csrf_field(); ?>
    <label>Title</label>
    <input class="input" name="title" value="<?php echo e($title); ?>" required>
    <label>Content (Markdown supported)</label>
    <textarea id="markdown" class="textarea" name="content" required><?php echo e($content); ?></textarea>
    <div style="margin:8px 0;">
      <a id="toggle-preview" href="#" class="btn secondary">Toggle Preview</a>
    </div>
    <div id="preview" class="card" style="display:none;"></div>
    <div style="margin-top:12px;">
      <button class="btn" type="submit">Save</button>
      <a class="btn secondary" href="<?php echo app_url('/posts/view.php?id='.(int)$id); ?>">Cancel</a>
    </div>
  </form>
</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
