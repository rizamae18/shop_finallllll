<?php
// Always show the login page first when opening the project folder in localhost.
// This does not change the working pages; it only controls the first page opened.
session_start();
session_unset();
session_destroy();

header('Location: login.php');
exit();
?>
