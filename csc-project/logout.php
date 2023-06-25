
<?php
session_start(); 
session_destroy();
echo "You have been logged out";
sleep(2); 
header('location: login.php');
?>