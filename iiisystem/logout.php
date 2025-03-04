<?php
session_start(); // Start session

// Destroy all the sessions
session_unset();
session_destroy();

// Redirect to login page (or homepage)
header("Location: ./index.php");
exit();
?>
