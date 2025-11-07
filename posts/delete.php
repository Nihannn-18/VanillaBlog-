<?php
require_once __DIR__.'/../includes/auth.php';
require_login();  // Only logged-in users can delete posts
require_once __DIR__.'/../includes/csrf.php';

// Allow only POST requests for deletion
if($_SERVER['REQUEST_METHOD']!=='POST'){ http_response_code(405); die('Method not allowed.'); }
verify_csrf();  // Verify CSRF token for form security

$id=(int)($_POST['id']??0);
if($id<=0){ http_response_code(400); die('Invalid request.'); }


// Check user permissions (admin or post owner only)
if(!is_admin() && !owns_post($pdo,$id,(int)$_SESSION['user_id'])){
    http_response_code(403); die('You are not authorized to delete this post.');
}

// Attempt to delete post from database
try{
    $stmt=$pdo->prepare('DELETE FROM blogPost WHERE id=?');
    $stmt->execute([$id]);
    flash_success('Post deleted.');
    redirect('/');
} catch (Throwable $e){
    http_response_code(500);
    die('Failed to delete the post.');
}
