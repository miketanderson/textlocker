<?php
session_start();
$user_check=$_SESSION['user_name'];

if(!isset($user_check))
{
  header("Location: auth.php");
}

?>
