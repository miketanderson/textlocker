<?php
#http://www.formget.com/login-form-in-php/
require_once('isActive.php');
$login_session=$_SESSION['user_name'];
$key = $_SESSION['user_key'];
$hash = $_SESSION['user_hash'];

#test data file
$new_file = false;

$data_file = '../data/' . $hash . '.dat';

if (isset($_POST['create'])) {
  $file = fopen($data_file, "w") or die("Unable to open file!");
  fclose($file);
}

if (file_exists($data_file) ) {
  #open file
} else {
  #flag to create file  
  $new_file = true; 
}

?>

<html>
<head>
<title>Data Viewer</title>
</head>
<body>
<div id="view">
<p><b id="welcome">Welcome: <i><?php echo $login_session; ?></i></b></p>
<p>
<?php 
#echo $key;
if ($new_file) {
  print '<p>It looks like you are missing a data file, click to create one</p>';
  print '<form action="viewer.php" method="post">';
  print '<input name="create" type="submit" value="Create File">';

}
 ?>
</p>
<?php require_once('footer.php') ?>
