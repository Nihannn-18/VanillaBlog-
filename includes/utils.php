<?php
// Build a full app URL based on base URL in .env
function app_url(string $path = ''): string {
    $base = rtrim($_ENV['APP_URL'] ?? '', '/');
    $path = '/'.ltrim($path,'/');
    return $base.$path;
}
// Redirect user to another page and stop execution
function redirect(string $path){ header('Location: '.app_url($path)); exit; }

// Escape output to prevent XSS attacks
function e(string $str): string { return htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); }

function json_input(): array { $raw=file_get_contents('php://input'); return $raw? json_decode($raw,true) ?? []:[]; }

// Store error message in session
function flash_error(string $msg){ $_SESSION['flash_error']=$msg; }

// Store success message in session
function flash_success(string $msg){ $_SESSION['flash_success']=$msg; }

function take_flash(string $key): ?string {
  if(!empty($_SESSION[$key])){ $m=$_SESSION[$key]; unset($_SESSION[$key]); return $m;}
  return null;
}
