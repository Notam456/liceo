<?php

session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// (Optional) Redirect to login page or home
header("Location: index.php");
exit;
?>