<?php
// Start session
session_start();

// Destroy the session to log out the user
session_unset();
session_destroy();

// Redirect to logout confirmation page
header("Location: vendor_logout_confirmation.php");
exit;
?>
