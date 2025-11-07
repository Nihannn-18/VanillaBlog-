<?php
// PDO connection + session setup + APP_URL variable for JS
require_once __DIR__.'/env.php';
require_once __DIR__.'/utils.php';
load_env(__DIR__.'/../.env');

session_name($_ENV['SESSION_NAME'] ?? 'BLOGSESSID');
session_set_cookie_params([
  'lifetime'=>0,'path'=>'/','httponly'=>true,'samesite'=>'Lax',
  'secure'=>isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']==='on',
]);
if(session_status()!==PHP_SESSION_ACTIVE) session_start();

try {
  $dsn='mysql:host='.($_ENV['DB_HOST']??'127.0.0.1')
      .';dbname='.($_ENV['DB_NAME']??'blog_db').';charset=utf8mb4';
  $pdo=new PDO($dsn, $_ENV['DB_USER']??'root', $_ENV['DB_PASS']??'', [
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
  ]);
} catch (PDOException $e) {
  http_response_code(500);
  die('Database connection failed. Please check .env settings.');
}

// expose APP_URL for frontend JS
echo '<script>window.APP_URL=' . json_encode(rtrim($_ENV['APP_URL'] ?? '', '/')) . ';</script>';
