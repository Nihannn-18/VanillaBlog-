<?php
// Include shared dependencies and helpers
require_once __DIR__.'/../includes/header.php';
require_once __DIR__.'/../includes/auth.php';
require_once __DIR__.'/../includes/csrf.php';
require_once __DIR__.'/../includes/markdown.php';

//only logged-in users can access this page
require_login();

$errors=[]; $title=''; $content='';

// Handle form submission
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf(); //Check CSRF token for form security

    // Sanitize and validate form inputs
    $title=trim($_POST['title']??'');
    $content=trim($_POST['content']??'');

    if(!$title||!$content) $errors[]='Title and content are required.';
    
    // If no errors, insert post into the database
    if(!$errors){
        try{
            $stmt=$pdo->prepare('INSERT INTO blogPost (user_id,title,content) VALUES (?,?,?)');
            $stmt->execute([$_SESSION['user_id'],$title,$content]);
            flash_success('Post published.');
            redirect('/');
        } catch (Throwable $e){
            $errors[]='Failed to create post.';
        }
    }
}
?>
<div class="card">
  <h1>New Post</h1>
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
      <button class="btn" type="submit">Publish</button>
      <a class="btn secondary" href="<?php echo app_url('/'); ?>">Cancel</a>
    </div>
  </form>
</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
