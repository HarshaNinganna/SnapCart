<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to the logout confirmation page
header("Location: user_logout_confirmation.php");
exit();
?>
