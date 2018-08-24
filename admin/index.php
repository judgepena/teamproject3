<?php
    require_once '../connection/connection.php';
   if(!is_logged_in()){
       header('Location: login.php');
    }
    include 'includes/head.php';
    include 'includes/navigation.php';
?>
Administrator Home
<?php include 'includes/footer.php'; ?>