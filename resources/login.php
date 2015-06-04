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
    $file_pass = '../data/' . $user_hash . '.chk';
    if (file_exists($file_salt) ) {
        #TODO: check password vs hash here
        if (file_exists($file_pass)) {
          $_SESSION['user_name']=$username;
          $salt = file_get_contents($file_salt); 
          $key = hash_password($password,$salt);
          #Use encrypted key, decrypt .chk file, validate file contents = "TRUE"
          $passchk = file_get_contents($file_pass);
          $decchk = aes_decrypt($key, $passchk); 
          if ($decchk === "TRUE") {
            $_SESSION['user_key']=$key;
            $_SESSION['user_hash']=$user_hash;
            header("location: text_view.php");
          }
          else {
              $error = "Username or password is invalid";
          }
        }

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
        
        #Use encrypted key, create user_hash.ckc file, encrypt($key, "TRUE") save to file
        if (file_exists($file_pass)) {
            #user already exists but new user flag is set, don't proceed
          $error = "Can't create user";
        }
        else {

          $fpass = fopen($file_pass, "w") or die("Unable to open file!");
          $encchk = aes_encrypt($key,"TRUE");
          fwrite($fpass,$encchk);
          fclose($fpass);

          $_SESSION['user_key']=$key;
          $_SESSION['user_hash']=$user_hash;
          header("location: text_view.php");
        }

      } else {
        $error = "Username or password is invalid";
      }
      #$error = $file_salt . " not found";
    }
  }
}

echo $error;
?>
