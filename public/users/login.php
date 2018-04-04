<?php
require_once('../../private/initialize.php');

$errors = [];
$username = '';
$password = '';

if(is_post_request()) {

  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  $type = $_POST['type'] ?? '';

  //Validations
  if(is_blank($username)){
    $errors[] = "Username cannot be blank.";
  }
  if(is_blank($password)){
    $errors[] = "Password cannot be blank.";
  }
  if(is_blank($type)){
    $errors[] = "Type of user cannot be blank.";
  }
  if(empty($errors)){
    if ($type == "org"){
      $sql = "SELECT * FROM users.Organizations WHERE username='" . $username . "' ";
      $sql .= "AND password='" . $password . "' LIMIT 1";
    }else{
      $sql = "SELECT * FROM users.Users WHERE username='" . $username . "' ";
      $sql .= "AND password='" . $password . "' LIMIT 1";
    }
    $user_set = mysqli_query($db, $sql);
    $user = mysqli_fetch_assoc($user_set);

    if ($user == NULL){
      $errors[]= "Username/password not found.";
    }else {
      mysqli_free_result($user_set);

      log_in($user, $type);
      redirect_to(url_for('/users/allEvents.php'));
    }
  }
}
?>
    <?php $page_title = 'Log in'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
    <div class="login-box">
        <h1 class='login-title'><a href="#">FOMO@UF</a></h1>
        <div class="login-errors">
            <em><?php echo display_errors($errors); ?></em>
        </div>
        <div class="login-content">
            <form action="login.php" method="post" autocomplete="off">
                <h1>Username</h1>
                <input type="text" name="username" value="<?php echo h($username); ?>" /><br/>
                <h1>Password</h1>
                <input type="password" name="password" value="" /><br/>
                <div class="button login">
                  <button><span>LOGIN</span></button>
                <!-- </div><a class="pass-forgot" href="">Forgot your password?</a> -->
                <input type="radio" name="type" id="student" value="student">
                <label for="student">Student</label>
                <input type="radio" name="type" id="org" value="org">
                <label for="org">Organization</label>
            </form>
        </div>
    </div>
    </html>
