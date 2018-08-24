<?php
$hostname = 'localhost';
$username = 'judge1991';
$password = '';
$databaseName = 'c7154275';
$connection = mysqli_connect($hostname, $username, $password, $databaseName) or exit("Unable to connect database");
 
 session_start();
 require_once '../config.php';
 require_once BASEURL.'../helpers/helpers.php';
 
 if(isset($_SESSION['SBUser'])){
  $user_id = $_SESSION['SBUser'];
  $query = $connection->query("SELECT * FROM users WHERE id = '$user_id'");
  $user_data = mysqli_fetch_assoc($query);
  $fn = explode(' ', $user_data['full_name']);
  $user_data['first'] = $fn [0];
  $user_data['last'] = $fn [1];
 }
 
 if(isset($_SESSION['success_flash'])){
  echo '<div class="bg-success"><p class="text-success text-center">'.$_SESSION['success_flash'].'</p></div>';
  unset($_SESSION['success_flash']);
 }
 
  if(isset($_SESSION['error_flash'])){
  echo '<div class="bg-danger"><p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
  unset($_SESSION['error_flash']);
 }
 
 ?>



