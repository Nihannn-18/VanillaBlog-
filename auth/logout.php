<?php
// Include session and utilities
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/utils.php';

// Clear and destroy current session
session_unset();
session_destroy();

flash_success('You have been logged out.');
redirect('/');
