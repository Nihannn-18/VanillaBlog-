<?php
// Include shared header and CSRF protection helper
require_once __DIR__.'/../includes/header.php';
require_once __DIR__.'/../includes/csrf.php';

// Array to hold backend validation errors
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();

    //Get user input
    $username=trim($_POST['username']??'');
    $email=trim($_POST['email']??'');
    $password=$_POST['password']??'';
    $confirm=$_POST['confirm']??'';


    //Backend validation
    if(!$username||!$email||!$password||!$confirm) $errors[]='All fields are required.';
    if($password!==$confirm) $errors[]='Passwords do not match.';
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Invalid email address.';

    if(!$errors){
        try{
            $stmt=$pdo->prepare('SELECT id FROM user WHERE email=? OR username=? LIMIT 1');
            $stmt->execute([$email,$username]);
            if($stmt->fetch()){
                $errors[]='Username or email already exists.';
            } else {
                // Hash password and insert new user
                $hash=password_hash($password,PASSWORD_DEFAULT);
                $pdo->prepare('INSERT INTO user (username,email,password,role) VALUES (?,?,?,?)')
                    ->execute([$username,$email,$hash,'user']);
                flash_success('Registration successful. You may log in.');
                redirect('/auth/login.php');
            }
        }catch(Throwable $e){
            $errors[]='Registration failed. Please try again.';
        }
    }
}
?>
<div class="card">
  <h1>Create account</h1>
  <?php foreach($errors as $e) echo '<div class="meta" style="color:#ffb3b3">'.e($e).'</div>'; ?>
  <form method="post" action="">
  <?php echo csrf_field(); ?>

  <div class="form-group">
    <label>Username</label>
    <input class="input" name="username" required value="<?php echo e($_POST['username'] ?? ''); ?>">
  </div>

  <div class="form-group">
    <label>Email</label>
    <input class="input" type="email" name="email" required value="<?php echo e($_POST['email'] ?? ''); ?>">
  </div>

  <div class="form-group">
    <label>Password</label>
    <input class="input" type="password" name="password" required>
  </div>

  <div class="form-group">
    <label>Confirm Password</label>
    <input class="input" type="password" name="confirm" required>
  </div>

  <div style="margin-top:12px;">
    <button class="btn" type="submit">Register</button>
    <a class="btn secondary" href="<?php echo app_url('/auth/login.php'); ?>">Login</a>
  </div>
</form>

</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
