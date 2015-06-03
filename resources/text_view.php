<?php
#http://www.formget.com/login-form-in-php/
require_once('isActive.php');
require_once('edit_text.php');
require_once('Parsedown/Parsedown.php');
$login_session=$_SESSION['user_name'];
$key = $_SESSION['user_key'];
$hash = $_SESSION['user_hash'];

#test data file
$new_file = false;
#txte file type is txt encrypted
$data_file = '../data/' . $hash . '.txte';

if (file_exists($data_file) ) {
  #open file
  $text = get_text($data_file, $key); 
} else {
  $error = 'There is no data file for this user yet.';
}

?>

<html>
<head>
<title>Data Viewer</title>
</head>
<body>
<div id="view">
<p><b id="welcome">Welcome: <i><?php echo $login_session; ?></i></b></p>
<hr>
<p>
<?php 
#echo $key;
 
  print '<p><div id="text_view">';
  $Parsedown = new Parsedown();
  print $Parsedown->text($text);
  #print $text;
  print '</p>';

  print $error;
 ?>
</p>
<?php require_once('footer.php') ?>
