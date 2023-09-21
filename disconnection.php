<?php 
    session_start();
    session_unset();
    session_destroy();

    setcookie('log', '', time()- 3600, '/');
    header('location: connection.php');exit();
?>