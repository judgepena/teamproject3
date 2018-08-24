<?php 
    require_once '../connection/connection.php';
    include 'includes/head.php';
    
    $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
    $email = trim($email);
    $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
    $password = trim($password);
    $errors = '';
    
    
?>
<style>
    body {
        background-image:url("../images/background.png");
        background-size: 100vw 100vh;
        background-attachment: fixed;
    }
</style>
<div id="login-form">
    <div>
        <?php if($_POST){
          if(empty($_POST['email']) || empty($_POST['password'])){
              $errors[] = 'Please enter email and password.';
          }
          
          //validate email
          if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
              $errors[] = 'Please enter a valid email';
          }
          
          //password is more than 6 characters
          if(strlen($password) < 6){
              $errors[] = 'Password must be at least 6 characters';
          }
          
          //Check email if exists in the database
          $query = $connection->query("SELECT * FROM users WHERE email = '$email'");
          $user = mysqli_fetch_assoc($query);
          $userCount = mysqli_num_rows($query);
          if ($userCount < 1){
              $errors[] = 'The email doen\'t exists in the database';
          }
          if (!password_verify($password, $user['password'])){
              $errors[] = 'The password does not match.';
          }
          //Form Validation
          if (!empty($errors)){
              echo display_errors($errors);
          }else{
              $user_id = $user['id'];
              login($user_id);
          }
        }
        ?>
    </div>
    <h2 class="text-center">Login</h2><hr>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo $email; ?>">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" value="<?php echo $password; ?>">
        </div>
        <div class="form-group">
        <input type="submit" value="Login" class="btn btn-primary">
        <input type="submit" value="Register" class="btn btn-primary">
        </div>
    </form>
    <p class="text-right"><a href="../index.php" alt="home">Visit Site</a></p>
    <a href="/teamproject3/index.php" alt="home">
</a>
</div>

<?php include 'includes/footer.php'; ?>
