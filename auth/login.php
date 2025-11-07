<?php

// Include common header and CSRF protection
require_once __DIR__.'/../includes/header.php';
require_once __DIR__.'/../includes/csrf.php';

$error=null;

//Handle form submission
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();  //Check CSRF token for form security

    $email=trim($_POST['email']??'');
    $password=$_POST['password']??'';

    try{
        //Check email exists in DB
        $stmt=$pdo->prepare('SELECT id,username,role,password FROM user WHERE email=? LIMIT 1');
        $stmt->execute([$email]);
        $u=$stmt->fetch();
        
        //Verify password
        if($u && password_verify($password,$u['password'])){

          //Start new session for user
            session_regenerate_id(true);
            $_SESSION['user_id']=(int)$u['id'];
            $_SESSION['username']=$u['username'];
            $_SESSION['role']=$u['role'];
            flash_success('Welcome, '.$u['username'].'!');
            redirect('/');
        } else {
            $error='Invalid credentials.';
        }
    } catch (Throwable $e){
        $error='Login failed. Please try again.';
    }
}
?>
<div class="card">
  <h1>Login</h1>
  <?php if($error) echo '<div class="meta" style="color:#ffb3b3">'.e($error).'</div>'; ?>
  <form method="post" action="">
    <?php echo csrf_field(); ?>
    <label>Email</label>
    <input class="input" type="email" name="email" required>
    <label>Password</label>
    <input class="input" type="password" name="password" required>
    <div style="margin-top:12px;">
      <button class="btn" type="submit">Login</button>
      <a class="btn secondary" href="<?php echo app_url('/auth/register.php'); ?>">Register</a>
    </div>
  </form>
</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
