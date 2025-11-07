<?php
// Include database and CSRF protection helpers
require_once __DIR__.'/db.php';
require_once __DIR__.'/csrf.php';


// Return current logged-in user info as an array
function current_user(): ?array {
    if (empty($_SESSION['user_id'])) return null;
    return ['id'=>(int)$_SESSION['user_id'],'username'=>$_SESSION['username']??'','role'=>$_SESSION['role']??'user'];
}

// Restrict access to logged-in users only
function require_login(){
    if(!current_user()){
        flash_error('Please login to continue.');
        redirect('/auth/login.php');
    }
}

// Check if the logged-in user owns a specific post
function is_admin(): bool { return ($_SESSION['role'] ?? 'user')==='admin'; }
function owns_post(PDO $pdo, int $post_id, int $user_id): bool {
    $stmt=$pdo->prepare('SELECT 1 FROM blogPost WHERE id=? AND user_id=?');
    $stmt->execute([$post_id,$user_id]);
    return (bool)$stmt->fetchColumn();
}
