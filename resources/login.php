<?php
require_once('aes.php');


session_start();
$error='';
if (isset($_POST['submit'])) {
  if (empty($_POST['username']) || (empty($_POST['password']))) {
    $error = "Enter both a username and password";
  }
  else {
    $username=$_POST['username'];
    $password=$_POST['password'];
    $pepper_file = '../data/pepper.conf';
    if (file_exists($pepper_file) ) {
      $pepper=file_get_contents($pepper_file);
    } else {
        $file = fopen($pepper_file, "w") or die("Unable to open file!");
        $pepper= bin2hex(openssl_random_pseudo_bytes(16));
        fwrite($file, $pepper);
        fclose($file);
    }
    $user_hash = hash('sha256',$pepper . $username);
    #if exist file = user_hash, login = true
    $file_salt = '../data/' . $user_hash . '.salt';
    if (file_exists($file_salt) ) {
      $_SESSION['user_name']=$username;
      $salt = file_get_contents($file_salt); 
      $key = hash_password($password,$salt);
      $_SESSION['user_key']=$key;
      $_SESSION['user_hash']=$user_hash;
      header("location: text_view.php");

    }
    else {
      if (isset($_POST['newuser'])) {
        #create new user
        $file = fopen($file_salt, "w") or die("Unable to open file!");
        #generate salt
        $salt = bin2hex(openssl_random_pseudo_bytes(16));
        fwrite($file, $salt);
        fclose($file);
        $_SESSION['user_name']=$username;
        $key = hash_password($password,$salt);
        $_SESSION['user_key']=$key;
        $_SESSION['user_hash']=$user_hash;
        header("location: viewer.php");

      } else {
        $error = "Username or password is invalid";
      }
      #uncomment to create new user
      #$error = $file_salt . " not found";
    }
  }
}

echo $error;
?>
