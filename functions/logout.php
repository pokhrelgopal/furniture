<?php
session_start(); // Start the session

// Destroy all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page (or another page)
header("Location: /furniture/login.php");
exit();
