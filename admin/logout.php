<?php 
    require_once '../connection/connection.php';
    unset($_SESSION['SBUser']);
    header('Location: login.php');
?>