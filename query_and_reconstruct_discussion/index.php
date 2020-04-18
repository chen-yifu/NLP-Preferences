<?php // Do not put any HTML above this line

session_start();
if ( isset($_POST['cancel'] ) ) {
  header("Location: index.php");
  return;
}

$stored_hash_1 = 'd63fb2fd1cf47e4d2ed5d9da54971c15'; // pw: v__t___a___
$stored_hash_2 = '87133423e9b119bca13f4db05847fa15'; // user: nlp-p_______

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['username']) && isset($_POST['pass']) ) {
  if ( strlen($_POST['username']) < 1 || strlen($_POST['pass']) < 1 ) {
    $_SESSION["error"] = "Username and password are required";
    error_log("Login fail ".$_POST['username']." $check");
    header("Location: index.php");
    return;
  } else {
    unset($_SESSION["user_id"]);
    $check_1 = hash('md5', $salt.$_POST['pass']);
    $check_2 = hash('md5', $salt.$_POST['username']);
    if ($check_1 === $stored_hash_1 && $check_2 === $stored_hash_2) {
      $_SESSION['user_id'] = "user";
      header("Location: query.php");
      return;
    } else {
      $_SESSION['error'] = "Incorrect username password combination (hint: pw is v***t***a***)";
      header("Location: index.php");
      return;
    }
  }
}

// if ( $check == $stored_hash ) {
//   $_SESSION["account"] = $_POST["username"];
//   $_SESSION["success"] = "Logged in.";
//   header("Location: index.php?name=".urlencode($_POST['username']));
//   error_log("Login success ".$_POST['username']);
//   return;
// } else {
//   $_SESSION["error"] = "Incorrect password";
//   error_log("Login fail ".$_POST['username']." $check");
//   header("Location: login.php");
//   return;
// }


// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
  <?php require_once "bootstrap.php"; ?>
  <title>NLP-Preferences Database</title>
</head>
<body>
  <div class="container">
    <h1>Please Log In</h1>
    <?php
    if ( isset($_SESSION["error"]) ) {
      echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
      unset($_SESSION["error"]);
    }
    ?>
    <form method="POST" action="index.php">
      <label for="username">Username</label>
      <input type="text" name="username" id="username"><br/>
      <label for="id_1723">Password</label>
      <input type="password" name="pass" id="id_1723"><br/>
      <input type="submit" onclick="return doValidate();" value="Log In">
      <input type="submit" name="cancel" value="Cancel">
    </form>
  </div>
</body>
<!-- <body>

<div class="container">
<h1>Please Log In To Check Automobiles</h1>
>


<form method="POST">
<label for="nam">User Name</label>
<input type="text" name="username" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>

<!Hint: The password is the four character sound a cat
makes (all lower case) followed by 123. -->
