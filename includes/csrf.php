<?php

// Generate a random CSRF token and store in session
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token']=bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}

// Add hidden CSRF token field to all POST forms
function csrf_field(): string { return '<input type="hidden" name="_token" value="'.e(csrf_token()).'">'; }

// Verify CSRF token from submitted form matches session token
function verify_csrf(): void {
    $token = $_POST['_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(400); die('Invalid CSRF token.');
    }
}
